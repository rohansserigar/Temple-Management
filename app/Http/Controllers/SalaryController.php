<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    /**
     * Show salary status and payout history.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'Admin' && $user->role !== 'Accountant')) {
            abort(403, 'Unauthorized access.');
        }

        // Determine previous month name/value
        $prevMonthVal = date('Y-m', strtotime('first day of last month'));
        $prevMonthName = date('F Y', strtotime('first day of last month'));

        // Fetch Priests with status and wallets
        $priests = DB::table('priests')
            ->join('users', 'priests.user_id', '=', 'users.id')
            ->select('users.id as user_id', 'users.name', 'priests.monthly_salary as base_salary', 'priests.wallet_balance', 'users.role')
            ->get();

        // Fetch Staff
        $staff = DB::table('staff')
            ->join('users', 'staff.user_id', '=', 'users.id')
            ->select('users.id as user_id', 'users.name', 'staff.salary as base_salary', 'staff.wallet_balance', 'users.role')
            ->get();

        // Fetch Accountants
        $accountants = DB::table('accountants')
            ->join('users', 'accountants.user_id', '=', 'users.id')
            ->select('users.id as user_id', 'users.name', 'accountants.salary as base_salary', DB::raw('0.00 as wallet_balance'), 'users.role')
            ->get();

        $employees = $priests->concat($staff)->concat($accountants);

        // Check who has already been paid for previous month
        $paidUserIds = DB::table('salary_payouts')
            ->where('salary_month', $prevMonthVal)
            ->pluck('user_id')
            ->toArray();

        // Payout history
        $payoutHistory = DB::table('salary_payouts')
            ->join('users', 'salary_payouts.user_id', '=', 'users.id')
            ->select('salary_payouts.*', 'users.name')
            ->orderBy('salary_payouts.created_at', 'desc')
            ->get();

        return view('admin.salaries', compact('employees', 'prevMonthVal', 'prevMonthName', 'paidUserIds', 'payoutHistory'));
    }

    /**
     * Sanction payouts for the previous month.
     */
    public function sanction(Request $request)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'Admin' && $user->role !== 'Accountant')) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $prevMonthVal = date('Y-m', strtotime('first day of last month'));
        $prevMonthName = date('F Y', strtotime('first day of last month'));

        // Fetch Priests, Staff and Accountants
        $priests = DB::table('priests')
            ->join('users', 'priests.user_id', '=', 'users.id')
            ->select('users.id as user_id', 'priests.priest_id', 'priests.monthly_salary as base_salary', 'priests.wallet_balance', 'users.role')
            ->get();

        $staff = DB::table('staff')
            ->join('users', 'staff.user_id', '=', 'users.id')
            ->select('users.id as user_id', 'staff.staff_id', 'staff.salary as base_salary', 'staff.wallet_balance', 'users.role')
            ->get();

        $accountants = DB::table('accountants')
            ->join('users', 'accountants.user_id', '=', 'users.id')
            ->select('users.id as user_id', 'accountants.salary as base_salary', DB::raw('0.00 as wallet_balance'), 'users.role')
            ->get();

        DB::beginTransaction();
        try {
            $sanctionedCount = 0;

            // Process Priests
            foreach ($priests as $p) {
                // Check if already paid
                $exists = DB::table('salary_payouts')
                    ->where('user_id', $p->user_id)
                    ->where('salary_month', $prevMonthVal)
                    ->exists();

                if ($exists) continue;

                $walletAmount = $p->wallet_balance;
                $totalPaid = max(0.00, $p->base_salary + $walletAmount);

                // Insert salary payout record
                DB::table('salary_payouts')->insert([
                    'user_id' => $p->user_id,
                    'role' => 'Priest',
                    'salary_month' => $prevMonthVal,
                    'base_salary' => $p->base_salary,
                    'wallet_amount' => $walletAmount,
                    'total_paid' => $totalPaid,
                    'payment_date' => date('Y-m-d'),
                    'payment_status' => 'Paid',
                    'remarks' => "Salary sanctioned for {$prevMonthName}. Wallet balance of ₹{$walletAmount} adjusted and cleared.",
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Clear Priest Wallet
                DB::table('priests')->where('priest_id', $p->priest_id)->update([
                    'wallet_balance' => 0.00,
                    'updated_at' => now()
                ]);

                // Insert wallet clearance transaction log
                if ($walletAmount != 0) {
                    DB::table('priest_wallet_transactions')->insert([
                        'priest_id' => $p->priest_id,
                        'amount' => abs($walletAmount),
                        'transaction_type' => ($walletAmount > 0) ? 'Debit' : 'Credit',
                        'remarks' => "Wallet balance cleared to 0.00 upon salary sanction for {$prevMonthName}",
                        'created_at' => now()
                    ]);
                }

                $sanctionedCount++;
            }

            // Process Staff
            foreach ($staff as $s) {
                $exists = DB::table('salary_payouts')
                    ->where('user_id', $s->user_id)
                    ->where('salary_month', $prevMonthVal)
                    ->exists();

                if ($exists) continue;

                $walletAmount = $s->wallet_balance;
                $totalPaid = max(0.00, $s->base_salary + $walletAmount);

                DB::table('salary_payouts')->insert([
                    'user_id' => $s->user_id,
                    'role' => 'Staff',
                    'salary_month' => $prevMonthVal,
                    'base_salary' => $s->base_salary,
                    'wallet_amount' => $walletAmount,
                    'total_paid' => $totalPaid,
                    'payment_date' => date('Y-m-d'),
                    'payment_status' => 'Paid',
                    'remarks' => "Salary sanctioned for {$prevMonthName}. Wallet balance of ₹{$walletAmount} adjusted and cleared.",
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('staff')->where('staff_id', $s->staff_id)->update([
                    'wallet_balance' => 0.00,
                    'updated_at' => now()
                ]);

                if ($walletAmount != 0) {
                    DB::table('staff_wallet_transactions')->insert([
                        'staff_id' => $s->staff_id,
                        'amount' => abs($walletAmount),
                        'transaction_type' => ($walletAmount > 0) ? 'Debit' : 'Credit',
                        'remarks' => "Wallet balance cleared to 0.00 upon salary sanction for {$prevMonthName}",
                        'created_at' => now()
                    ]);
                }

                $sanctionedCount++;
            }

            // Process Accountants
            foreach ($accountants as $a) {
                $exists = DB::table('salary_payouts')
                    ->where('user_id', $a->user_id)
                    ->where('salary_month', $prevMonthVal)
                    ->exists();

                if ($exists) continue;

                DB::table('salary_payouts')->insert([
                    'user_id' => $a->user_id,
                    'role' => 'Accountant',
                    'salary_month' => $prevMonthVal,
                    'base_salary' => $a->base_salary,
                    'wallet_amount' => 0.00,
                    'total_paid' => $a->base_salary,
                    'payment_date' => date('Y-m-d'),
                    'payment_status' => 'Paid',
                    'remarks' => "Salary sanctioned for {$prevMonthName}.",
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $sanctionedCount++;
            }

            DB::commit();
            return redirect()->back()->with('success', "Successfully sanctioned salary payouts for {$sanctionedCount} employees for {$prevMonthName}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Failed to sanction payouts: " . $e->getMessage());
        }
    }

    /**
     * Display reporting dashboards.
     */
    public function reports(Request $request)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'Admin' && $user->role !== 'Accountant')) {
            abort(403, 'Unauthorized access.');
        }

        // 1. Attendance report
        $priestAttendance = DB::table('priest_attendance')
            ->select('attendance_date', DB::raw("SUM(CASE WHEN attendance_status='Present' THEN 1 ELSE 0 END) as present_count"), DB::raw("SUM(worked_hours) as total_hours"))
            ->groupBy('attendance_date')
            ->orderBy('attendance_date', 'desc')
            ->limit(30)
            ->get();

        $staffAttendance = DB::table('staff_attendance')
            ->select('attendance_date', DB::raw("SUM(CASE WHEN attendance_status='Present' THEN 1 ELSE 0 END) as present_count"), DB::raw("SUM(worked_hours) as total_hours"))
            ->groupBy('attendance_date')
            ->orderBy('attendance_date', 'desc')
            ->limit(30)
            ->get();

        // 2. Salary payout report
        $salaryPayoutsSummary = DB::table('salary_payouts')
            ->select('salary_month', DB::raw("SUM(base_salary) as total_base"), DB::raw("SUM(wallet_amount) as total_wallet"), DB::raw("SUM(total_paid) as total_paid"))
            ->groupBy('salary_month')
            ->orderBy('salary_month', 'desc')
            ->get();

        // 3. Wallet transactions report
        $priestWalletTx = DB::table('priest_wallet_transactions')
            ->select(DB::raw("DATE(created_at) as date"), DB::raw("SUM(CASE WHEN transaction_type='Credit' THEN amount ELSE 0 END) as credits"), DB::raw("SUM(CASE WHEN transaction_type='Debit' THEN amount ELSE 0 END) as debits"))
            ->groupBy(DB::raw("DATE(created_at)"))
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        $staffWalletTx = DB::table('staff_wallet_transactions')
            ->select(DB::raw("DATE(created_at) as date"), DB::raw("SUM(CASE WHEN transaction_type='Credit' THEN amount ELSE 0 END) as credits"), DB::raw("SUM(CASE WHEN transaction_type='Debit' THEN amount ELSE 0 END) as debits"))
            ->groupBy(DB::raw("DATE(created_at)"))
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        // 4. Pooja Completion report
        $poojaCompletionSummary = DB::table('pooja_bookings')
            ->join('poojas', 'pooja_bookings.pooja_id', '=', 'poojas.pooja_id')
            ->select('poojas.pooja_name', DB::raw("COUNT(*) as completed_count"), DB::raw("SUM(pooja_bookings.total_amount) as total_amount"))
            ->where('pooja_bookings.booking_status', 'Completed')
            ->groupBy('poojas.pooja_name')
            ->orderBy('completed_count', 'desc')
            ->get();

        // 5. Monthly Earnings report (from Bookings and Donations)
        $bookingsEarnings = DB::table('pooja_bookings')
            ->select(DB::raw("DATE_FORMAT(booking_date, '%Y-%m') as month"), DB::raw("SUM(total_amount) as total_earnings"))
            ->where('payment_status', 'Paid')
            ->groupBy('month')
            ->get();

        // Check dynamically if donations table exists
        $donationsEarnings = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('donations')) {
            $donationsEarnings = DB::table('donations')
                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw("SUM(amount) as total_earnings"))
                ->groupBy('month')
                ->get();
        }

        return view('admin.reports', compact(
            'priestAttendance',
            'staffAttendance',
            'salaryPayoutsSummary',
            'priestWalletTx',
            'staffWalletTx',
            'poojaCompletionSummary',
            'bookingsEarnings',
            'donationsEarnings'
        ));
    }
}
