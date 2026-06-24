<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Models\Setting;


class DevoteeController extends Controller
{

public function dashboard()
{
    $user = Auth::user();


    $devotee = DB::table('devotees')
        ->where('user_id',$user->id)
        ->first();


    if(!$devotee)
{
    return view('devotee.dashboard',[
        'user' => $user,
        'devotee' => null,
        'membership' => null,
        'poojaCount' => 0,
        'upcomingPoojas' => 0,
        'totalDonation' => 0,
        'recentDonations' => collect(),
        'recentBookings' => collect(),
        'events' => DB::table('events')
                        ->where('status','Upcoming')
                        ->limit(3)
                        ->get()
    ]);
}


    $membership = DB::table('memberships')
        ->where('membership_id',$devotee->membership_id)
        ->first();



    $poojaCount = DB::table('pooja_bookings')
        ->where('devotee_id',$devotee->devotee_id)
        ->count();



    $upcomingPoojas = DB::table('pooja_bookings')
        ->where('devotee_id',$devotee->devotee_id)
        ->where('booking_date','>=',date('Y-m-d'))
        ->count();



    $totalDonation = DB::table('donations')
        ->where('devotee_id',$devotee->devotee_id)
        ->sum('amount');



    $recentDonations = DB::table('donations')
        ->where('devotee_id',$devotee->devotee_id)
        ->orderBy('donation_date','desc')
        ->limit(3)
        ->get();



    $recentBookings = DB::table('pooja_bookings')
        ->join('poojas', 'pooja_bookings.pooja_id', '=', 'poojas.pooja_id')
        ->leftJoin('priests', 'pooja_bookings.priest_id', '=', 'priests.priest_id')
        ->leftJoin('users as priest_user', 'priests.user_id', '=', 'priest_user.id')
        ->where('pooja_bookings.devotee_id', $devotee->devotee_id)
        ->select(
            'pooja_bookings.booking_id',
            'poojas.pooja_name',
            'pooja_bookings.booking_date',
            'pooja_bookings.booking_time',
            'pooja_bookings.booking_type',
            'pooja_bookings.payment_status',
            'pooja_bookings.booking_status',
            'priest_user.name as priest_name'
        )
        ->orderBy('pooja_bookings.booking_date', 'desc')
        ->orderBy('pooja_bookings.booking_time', 'desc')
        ->get();



    $events = DB::table('events')
        ->where('status','Upcoming')
        ->limit(3)
        ->get();



    return view(
        'devotee.dashboard',
        compact(
            'user',
            'devotee',
            'membership',
            'poojaCount',
            'upcomingPoojas',
            'totalDonation',
            'recentDonations',
            'recentBookings',
            'events'
        )
    );
}

    // ============================================
    // ADMIN DEVOTEE CRUD OPERATIONS
    // ============================================

    public function manageDevotees(Request $request)
    {
        $status = $request->get('verification_status');

        $query = DB::table('devotees')
            ->join('users', 'devotees.user_id', '=', 'users.id')
            ->leftJoin('memberships', 'devotees.membership_id', '=', 'memberships.membership_id')
            ->select(
                'devotees.*',
                'users.name',
                'users.email',
                'users.mobile',
                'users.email_verified_at',
                'memberships.membership_name'
            );

        if ($status === 'Verified') {
            $query->whereNotNull('users.email_verified_at');
        } elseif ($status === 'Unverified') {
            $query->whereNull('users.email_verified_at');
        }

        $devotees = $query->get();

        $memberships = DB::table('memberships')->where('status', 'Active')->get();

        return view('admin.manage-devotees', compact('devotees', 'memberships', 'status'));
    }

    public function addDevoteePage()
    {
        $memberships = DB::table('memberships')->where('status', 'Active')->get();
        return view('admin.add-devotee', compact('memberships'));
    }

    public function storeDevotee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|string|max:15|unique:users,mobile',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'dob' => 'nullable|date',
            'gothra' => 'nullable|string|max:100',
            'nakshatra' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'membership_id' => 'nullable|exists:memberships,membership_id',
            'verified' => 'required|boolean',
        ]);

        $password = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::beginTransaction();

        try {
            $existingUser = DB::table('users')
                ->where('email', $request->email)
                ->orWhere('mobile', $request->mobile)
                ->first();

            if ($existingUser) {
                $existingDevotee = DB::table('devotees')
                    ->where('user_id', $existingUser->id)
                    ->first();

                if ($existingDevotee) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'This user is already registered as a Devotee.')
                        ->withInput();
                }

                DB::table('users')
                    ->where('id', $existingUser->id)
                    ->update([
                        'role' => 'Devotee',
                        'status' => 'Active',
                        'email_verified_at' => now(),
                        'updated_at' => now()
                    ]);

                $userId = $existingUser->id;
            } else {
                $userId = DB::table('users')->insertGetId([
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'password' => \Illuminate\Support\Facades\Hash::make($password),
                    'role' => 'Devotee',
                    'status' => 'Active',
                    'must_change_password' => 1,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Membership dates calculation
            $membershipStartDate = null;
            $membershipEndDate = null;
            if ($request->membership_id) {
                $membership = DB::table('memberships')->where('membership_id', $request->membership_id)->first();
                if ($membership) {
                    $membershipStartDate = date('Y-m-d');
                    $months = $membership->duration_months ?? 1;
                    $membershipEndDate = date('Y-m-d', strtotime("+$months months"));
                }
            }

            DB::table('devotees')->insert([
                'user_id' => $userId,
                'gothra' => $request->gothra,
                'nakshatra' => $request->nakshatra,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'address' => $request->address,
                'membership_id' => $request->membership_id,
                'membership_start_date' => $membershipStartDate,
                'membership_end_date' => $membershipEndDate,
                'verified' => $request->verified,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            // Handling System Mode
            $systemMode = Setting::get('system_mode', 'Testing Mode');
            $emailHandling = Setting::get('testing_email_handling', 'Do Not Send Emails');

            $sendEmail = false;
            $flashPassword = false;

            if ($systemMode === 'Testing Mode') {
                $flashPassword = true;
                if ($emailHandling === 'Send Emails') {
                    $sendEmail = true;
                }
            } else {
                $sendEmail = true;
            }

            if ($sendEmail) {
                try {
                    Mail::to($request->email)->send(new WelcomeMail($request->name, 'Devotee', $request->email, $password));
                } catch (\Exception $e) {
                    // Ignore mail errors
                }
            }

            $message = $existingUser ? 'User promoted to Devotee successfully!' : 'Devotee Added Successfully!';

            if ($flashPassword) {
                return redirect()->route('admin.devotees.index')
                    ->with('success', $message)
                    ->with('success_user_created', [
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => $password,
                        'role' => 'Devotee'
                    ]);
            } else {
                return redirect()->route('admin.devotees.index')->with('success', $message);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to add devotee: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateDevotee(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'required|email',
            'gothra' => 'nullable|string|max:100',
            'nakshatra' => 'nullable|string|max:100',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'dob' => 'nullable|date',
            'address' => 'nullable|string',
            'membership_id' => 'nullable|exists:memberships,membership_id',
            'verified' => 'required|boolean',
        ]);

        DB::beginTransaction();

        try {
            $devotee = DB::table('devotees')->where('devotee_id', $id)->first();
            if (!$devotee) {
                return redirect()->back()->with('error', 'Devotee not found.');
            }

            // Verify unique email/mobile among other users
            $dupEmail = DB::table('users')->where('email', $request->email)->where('id', '!=', $devotee->user_id)->first();
            if ($dupEmail) {
                return redirect()->back()->with('error', 'Email address is already in use.')->withInput();
            }

            $dupMobile = DB::table('users')->where('mobile', $request->mobile)->where('id', '!=', $devotee->user_id)->first();
            if ($dupMobile) {
                return redirect()->back()->with('error', 'Mobile number is already in use.')->withInput();
            }

            $membershipStartDate = $devotee->membership_start_date;
            $membershipEndDate = $devotee->membership_end_date;

            if ($request->membership_id != $devotee->membership_id) {
                if ($request->membership_id) {
                    $membership = DB::table('memberships')->where('membership_id', $request->membership_id)->first();
                    if ($membership) {
                        $membershipStartDate = date('Y-m-d');
                        $months = $membership->duration_months ?? 1;
                        $membershipEndDate = date('Y-m-d', strtotime("+$months months"));
                    }
                } else {
                    $membershipStartDate = null;
                    $membershipEndDate = null;
                }
            }

            DB::table('devotees')
                ->where('devotee_id', $id)
                ->update([
                    'gothra' => $request->gothra,
                    'nakshatra' => $request->nakshatra,
                    'gender' => $request->gender,
                    'dob' => $request->dob,
                    'address' => $request->address,
                    'membership_id' => $request->membership_id,
                    'membership_start_date' => $membershipStartDate,
                    'membership_end_date' => $membershipEndDate,
                    'verified' => $request->verified,
                    'updated_at' => now()
                ]);

            DB::table('users')
                ->where('id', $devotee->user_id)
                ->update([
                    'name' => $request->name,
                    'mobile' => $request->mobile,
                    'email' => $request->email,
                    'updated_at' => now()
                ]);

            DB::commit();

            return redirect()->route('admin.devotees.index')
                ->with('success', 'Devotee Updated Successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update devotee: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function deleteDevotee($id)
    {
        DB::beginTransaction();

        try {
            $devotee = DB::table('devotees')
                ->where('devotee_id', $id)
                ->first();

            if (!$devotee) {
                return redirect()->back()->with('error', 'Devotee not found.');
            }

            DB::table('devotees')
                ->where('devotee_id', $id)
                ->delete();

            DB::table('users')
                ->where('id', $devotee->user_id)
                ->delete();

            DB::commit();

            return redirect()->route('admin.devotees.index')
                ->with('success', 'Devotee Deleted Successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete devotee: ' . $e->getMessage());
        }
    }
}