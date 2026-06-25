<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    /**
     * Display the donations management panel.
     */
    public function manageDonations(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'Admin') {
            abort(403, 'Unauthorized access.');
        }

        // Fetch Devotee Donations
        $devoteeDonations = DB::table('donations')
            ->join('devotees', 'donations.devotee_id', '=', 'devotees.devotee_id')
            ->join('users', 'devotees.user_id', '=', 'users.id')
            ->select('donations.*', 'users.name as devotee_name', 'users.email', 'users.mobile')
            ->orderBy('donation_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch Guest Donations
        $guestDonations = DB::table('donations_without_logins')
            ->orderBy('donation_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate Totals
        $devoteeTotal = $devoteeDonations->sum('amount');
        $guestTotal = $guestDonations->sum('amount');
        $ehundiTotal = DB::table('ehundis')->sum('amount') ?: 0;
        $grandTotal = $devoteeTotal + $guestTotal + $ehundiTotal;

        // Fetch e-Hundi Donations
        $ehundiDonations = DB::table('ehundis')
            ->leftJoin('devotees', 'ehundis.devotee_id', '=', 'devotees.devotee_id')
            ->leftJoin('users', 'devotees.user_id', '=', 'users.id')
            ->select('ehundis.*', 'users.name as devotee_name', 'users.email', 'users.mobile')
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch Devotees list for the dropdown
        $devotees = DB::table('devotees')
            ->join('users', 'devotees.user_id', '=', 'users.id')
            ->select('devotees.devotee_id', 'users.name', 'users.email')
            ->orderBy('users.name', 'asc')
            ->get();

        return view('admin.manage-donations', compact(
            'devoteeDonations',
            'guestDonations',
            'ehundiDonations',
            'devoteeTotal',
            'guestTotal',
            'ehundiTotal',
            'grandTotal',
            'devotees'
        ));
    }

    /**
     * Store a manually recorded Devotee donation.
     */
    public function storeDevoteeDonation(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'Admin') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $validated = $request->validate([
            'devotee_id' => 'required|exists:devotees,devotee_id',
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required|string|in:Cash,UPI,Bank Transfer,Cheque',
            'transaction_id' => 'nullable|string|max:100',
            'remarks' => 'nullable|string|max:255',
            'donation_date' => 'required|date',
        ]);

        try {
            DB::table('donations')->insert([
                'devotee_id' => $validated['devotee_id'],
                'amount' => $validated['amount'],
                'payment_mode' => $validated['payment_mode'],
                'transaction_id' => $validated['transaction_id'] ?? 'OFFLINE-' . strtoupper(uniqid()),
                'remarks' => $validated['remarks'] ?? 'Manually recorded donation',
                'donation_date' => $validated['donation_date'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Devotee donation recorded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to record donation: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Store a manually recorded Guest donation.
     */
    public function storeGuestDonation(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'Admin') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile' => 'nullable|string|max:20',
            'amount' => 'required|numeric|min:1',
            'purpose' => 'required|string|max:100',
            'purpose_details' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:Cash,UPI,Bank',
            'transaction_id' => 'nullable|string|max:100',
            'bank_name' => 'required_if:payment_method,Bank|nullable|string|max:100',
            'bank_account_no' => 'required_if:payment_method,Bank|nullable|string|max:50',
            'bank_ifsc' => 'required_if:payment_method,Bank|nullable|string|max:20',
            'bank_branch' => 'required_if:payment_method,Bank|nullable|string|max:100',
            'donation_date' => 'required|date',
        ]);

        try {
            DB::table('donations_without_logins')->insert([
                'donor_name' => $validated['donor_name'],
                'email' => $validated['email'] ?? null,
                'mobile' => $validated['mobile'] ?? null,
                'amount' => $validated['amount'],
                'purpose' => $validated['purpose'],
                'purpose_details' => $validated['purpose_details'] ?? null,
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'] ?? 'GUEST-' . strtoupper(uniqid()),
                'bank_name' => $validated['bank_name'] ?? null,
                'bank_account_no' => $validated['bank_account_no'] ?? null,
                'bank_ifsc' => $validated['bank_ifsc'] ?? null,
                'bank_branch' => $validated['bank_branch'] ?? null,
                'donation_date' => $validated['donation_date'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Guest donation recorded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to record guest donation: ' . $e->getMessage())->withInput();
        }
    }
}
