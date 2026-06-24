<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DevoteeController;
use App\Http\Controllers\PriestController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TrusteeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AccountantController;

// ============================================
// PUBLIC ROUTES
// ============================================
Route::get('/', function () {
    if (Auth::check()) {
        $role = session('active_role') ?? Auth::user()->role;
        switch ($role) {
            case 'Admin':
                return redirect()->route('admin.dashboard');
            case 'Priest':
                return redirect()->route('priest.dashboard');
            case 'Trustee':
                return redirect()->route('trustee.dashboard');
            case 'Staff':
                return redirect()->route('staff.dashboard');
            case 'Accountant':
                return redirect()->route('accountant.dashboard');
            case 'Devotee':
            default:
                return redirect()->route('devotee.dashboard');
        }
    }

    $poojas = \Illuminate\Support\Facades\DB::table('poojas')->where('status', 'Active')->get();
    $events = \Illuminate\Support\Facades\DB::table('events')->where('status', 'Upcoming')->orderBy('event_date', 'asc')->get();
    
    $loggedDonations = \Illuminate\Support\Facades\DB::table('donations')->sum('amount') ?: 0;
    $guestDonations = \Illuminate\Support\Facades\DB::table('donations_without_logins')->sum('amount') ?: 0;
    $totalDonations = $loggedDonations + $guestDonations;

    return view('frontend.index', compact('poojas', 'events', 'totalDonations'));
})->name('home');

Route::post('/donate-without-login', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'donor_name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'mobile' => 'nullable|string|max:20',
        'amount' => 'required|numeric|min:1',
        'purpose' => 'required|string|max:100',
        'purpose_details' => 'nullable|string|max:255',
        'payment_method' => 'required|in:Bank,UPI',
        'transaction_id' => 'required|string|max:100',
        'bank_name' => 'required_if:payment_method,Bank|nullable|string|max:100',
        'bank_account_no' => 'required_if:payment_method,Bank|nullable|string|max:50',
        'bank_ifsc' => 'required_if:payment_method,Bank|nullable|string|max:20',
        'bank_branch' => 'required_if:payment_method,Bank|nullable|string|max:100',
    ]);

    \Illuminate\Support\Facades\DB::table('donations_without_logins')->insert([
        'donor_name' => $validated['donor_name'],
        'email' => $validated['email'] ?? null,
        'mobile' => $validated['mobile'] ?? null,
        'amount' => $validated['amount'],
        'purpose' => $validated['purpose'],
        'purpose_details' => $validated['purpose_details'] ?? null,
        'payment_method' => $validated['payment_method'],
        'transaction_id' => $validated['transaction_id'],
        'bank_name' => $validated['bank_name'] ?? null,
        'bank_account_no' => $validated['bank_account_no'] ?? null,
        'bank_ifsc' => $validated['bank_ifsc'] ?? null,
        'bank_branch' => $validated['bank_branch'] ?? null,
        'donation_date' => now()->toDateString(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->back()->with('success_donation', 'Thank you! Your donation of ₹' . number_format($validated['amount'], 2) . ' has been recorded successfully.');
})->name('donate.without.login');

// ============================================
// AUTHENTICATION ROUTES
// ============================================
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/register/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('register.verify-otp');
Route::post('/register/verify-otp', [AuthController::class, 'verifyOtp'])->name('register.verify-otp.post');
Route::post('/register/resend-otp', [AuthController::class, 'resendOtp'])->name('register.resend-otp');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// FORGOT PASSWORD SYSTEM ROUTES
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password.post');
Route::get('/forgot-password/verify', [AuthController::class, 'showVerifyForgotPasswordOtp'])->name('forgot-password.verify');
Route::post('/forgot-password/verify', [AuthController::class, 'verifyForgotPasswordOtp'])->name('forgot-password.verify.post');
Route::post('/forgot-password/resend', [AuthController::class, 'forgotPasswordResend'])->name('forgot-password.resend');
Route::get('/forgot-password/reset', [AuthController::class, 'showResetPassword'])->name('forgot-password.reset');
Route::post('/forgot-password/reset', [AuthController::class, 'resetPassword'])->name('forgot-password.reset.post');

// ============================================
// ADMIN ROUTES (Restricted via RoleMiddleware)
// ============================================
Route::middleware(['auth', 'role.admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        $devoteesCount = \Illuminate\Support\Facades\DB::table('devotees')->count();
        $priestsCount = \Illuminate\Support\Facades\DB::table('priests')->count();
        
        // Priest status counts
        $onlinePriests = \Illuminate\Support\Facades\DB::table('priests')->where('current_status', 'Online')->count();
        $busyPriests = \Illuminate\Support\Facades\DB::table('priests')->where('current_status', 'Busy')->count();
        $offlinePriests = \Illuminate\Support\Facades\DB::table('priests')->where('current_status', 'Offline')->count();
        $today = date('Y-m-d');
        $leavePriests = \Illuminate\Support\Facades\DB::table('leave_requests')
            ->where('status', 'Approved')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->distinct('priest_id')
            ->count('priest_id');
        
        // Today's Poojas count
        $todayPoojasCount = \Illuminate\Support\Facades\DB::table('pooja_bookings')
            ->where('booking_date', date('Y-m-d'))
            ->where('booking_status', '!=', 'Cancelled')
            ->count();

        // Donations sum
        $totalDonationsSum = \Illuminate\Support\Facades\DB::table('donations')->sum('amount') 
            + \Illuminate\Support\Facades\DB::table('donations_without_logins')->sum('amount');

        if ($totalDonationsSum >= 100000) {
            $donationsDisplay = '₹' . round($totalDonationsSum / 100000, 2) . 'L';
        } elseif ($totalDonationsSum >= 1000) {
            $donationsDisplay = '₹' . round($totalDonationsSum / 1000, 1) . 'K';
        } else {
            $donationsDisplay = '₹' . number_format($totalDonationsSum);
        }

        // Events count
        $eventsCount = \Illuminate\Support\Facades\DB::table('events')->where('status', 'Upcoming')->count();

        // Recent Devotees
        $recentDevotees = \Illuminate\Support\Facades\DB::table('devotees')
            ->join('users', 'devotees.user_id', '=', 'users.id')
            ->select('users.name', 'users.mobile', 'devotees.created_at')
            ->orderBy('devotees.created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Recent Donations from logged in devotees
        $loggedDonations = \Illuminate\Support\Facades\DB::table('donations')
            ->leftJoin('devotees', 'donations.devotee_id', '=', 'devotees.devotee_id')
            ->leftJoin('users', 'devotees.user_id', '=', 'users.id')
            ->select('users.name as donor_name', 'donations.amount', 'donations.donation_date', 'donations.created_at')
            ->orderBy('donations.donation_date', 'desc')
            ->orderBy('donations.created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent Donations from guest donors
        $guestDonations = \Illuminate\Support\Facades\DB::table('donations_without_logins')
            ->select('donor_name', 'amount', 'donation_date', 'created_at')
            ->orderBy('donation_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentDonations = $loggedDonations->concat($guestDonations)
            ->sort(function ($a, $b) {
                $cmp = strcmp($b->donation_date, $a->donation_date);
                if ($cmp !== 0) {
                    return $cmp;
                }
                return strcmp($b->created_at, $a->created_at);
            })
            ->take(5)
            ->values();
            
        return view('admin.dashboard', compact(
            'devoteesCount',
            'priestsCount',
            'onlinePriests',
            'busyPriests',
            'offlinePriests',
            'leavePriests',
            'todayPoojasCount',
            'donationsDisplay',
            'eventsCount',
            'recentDevotees',
            'recentDonations'
        ));
    })->name('admin.dashboard');

    // Priest Routes (Admin management)
    Route::get('/admin/manage-priests', [PriestController::class, 'managePriests'])->name('admin.priests.index');
    Route::get('/admin/add-priest', [PriestController::class, 'addPriestPage'])->name('admin.priests.create');
    Route::post('/admin/priest/store', [PriestController::class, 'storePriest'])->name('admin.priests.store');
    Route::get('/admin/priest/view/{id}', [PriestController::class, 'viewPriest'])->name('admin.priests.view');
    Route::get('/admin/priest/edit/{id}', [PriestController::class, 'editPriest'])->name('admin.priests.edit');
    Route::post('/admin/priest/update/{id}', [PriestController::class, 'updatePriest'])->name('admin.priests.update');
    Route::delete('/admin/priest/delete/{id}', [PriestController::class, 'deletePriest'])->name('admin.priests.delete');

    // Devotee Routes (Admin management)
    Route::get('/admin/manage-devotees', [DevoteeController::class, 'manageDevotees'])->name('admin.devotees.index');
    Route::get('/admin/add-devotee', [DevoteeController::class, 'addDevoteePage'])->name('admin.devotees.create');
    Route::post('/admin/devotee/store', [DevoteeController::class, 'storeDevotee'])->name('admin.devotees.store');
    Route::post('/admin/devotee/update/{id}', [DevoteeController::class, 'updateDevotee'])->name('admin.devotees.update');
    Route::delete('/admin/devotee/delete/{id}', [DevoteeController::class, 'deleteDevotee'])->name('admin.devotees.delete');

    // Admin Booking Management Routes
    Route::get('/admin/manage-bookings', [BookingController::class, 'manageBookings'])->name('admin.bookings.index');
    Route::post('/admin/bookings/override-priest/{id}', [BookingController::class, 'overridePriest'])->name('admin.bookings.override-priest');
    Route::post('/admin/bookings/reschedule/{id}', [BookingController::class, 'reschedule'])->name('admin.bookings.reschedule');
    Route::post('/admin/bookings/status/{id}', [BookingController::class, 'updateStatus'])->name('admin.bookings.update-status');

    // Trustee CRUD Routes (Admin management)
    Route::get('/admin/manage-trustees', [TrusteeController::class, 'manageTrustees'])->name('admin.trustees.index');
    Route::get('/admin/add-trustee', [TrusteeController::class, 'addTrusteePage'])->name('admin.trustees.create');
    Route::post('/admin/trustee/store', [TrusteeController::class, 'storeTrustee'])->name('admin.trustees.store');
    Route::post('/admin/trustee/update/{id}', [TrusteeController::class, 'updateTrustee'])->name('admin.trustees.update');
    Route::delete('/admin/trustee/delete/{id}', [TrusteeController::class, 'deleteTrustee'])->name('admin.trustees.delete');

    // Staff CRUD Routes (Admin management)
    Route::get('/admin/manage-staff', [StaffController::class, 'manageStaff'])->name('admin.staff.index');
    Route::get('/admin/add-staff', [StaffController::class, 'addStaffPage'])->name('admin.staff.create');
    Route::post('/admin/staff/store', [StaffController::class, 'storeStaff'])->name('admin.staff.store');
    Route::post('/admin/staff/update/{id}', [StaffController::class, 'updateStaff'])->name('admin.staff.update');
    Route::delete('/admin/staff/delete/{id}', [StaffController::class, 'deleteStaff'])->name('admin.staff.delete');

    // Accountant CRUD Routes (Admin management)
    Route::get('/admin/manage-accountants', [AccountantController::class, 'manageAccountants'])->name('admin.accountants.index');
    Route::get('/admin/add-accountant', [AccountantController::class, 'addAccountantPage'])->name('admin.accountants.create');
    Route::post('/admin/accountant/store', [AccountantController::class, 'storeAccountant'])->name('admin.accountants.store');
    Route::post('/admin/accountant/update/{id}', [AccountantController::class, 'updateAccountant'])->name('admin.accountants.update');
    Route::delete('/admin/accountant/delete/{id}', [AccountantController::class, 'deleteAccountant'])->name('admin.accountants.delete');

    // Admin Settings Routes
    Route::get('/admin/settings', function () {
        $systemMode = \App\Models\Setting::get('system_mode', 'Testing Mode');
        $emailHandling = \App\Models\Setting::get('testing_email_handling', 'Do Not Send Emails');
        return view('admin.settings', compact('systemMode', 'emailHandling'));
    })->name('admin.settings');

    Route::post('/admin/settings', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'system_mode' => 'required|in:Testing Mode,Live Mode',
            'testing_email_handling' => 'required_if:system_mode,Testing Mode|nullable|in:Send Emails,Do Not Send Emails',
        ]);

        \App\Models\Setting::set('system_mode', $request->system_mode);
        if ($request->has('testing_email_handling')) {
            \App\Models\Setting::set('testing_email_handling', $request->testing_email_handling);
        }

        return redirect()->back()->with('success', 'System settings updated successfully.');
    })->name('admin.settings.update');

    // Leave Requests Route (Admin management)
    Route::get('/admin/manage-leaves', [TrusteeController::class, 'manageLeaves'])->name('admin.leaves.index');
    Route::post('/admin/leaves/status/{id}', [PriestController::class, 'updateLeaveStatus'])->name('admin.leaves.status');
});

// ============================================
// DEVOTEE ROUTES (Restricted via RoleMiddleware)
// ============================================
Route::middleware(['auth', 'role.devotee'])->group(function () {
    Route::get('/devotee/dashboard', [DevoteeController::class, 'dashboard'])->name('devotee.dashboard');

    // Devotee Pooja Booking Routes
    Route::get('/devotee/book-pooja', [BookingController::class, 'bookPoojaPage'])->name('devotee.book-pooja');
    Route::get('/devotee/book_pooja', [BookingController::class, 'bookPoojaPage']);
    Route::post('/devotee/book-pooja', [BookingController::class, 'storeBooking'])->name('devotee.book-pooja.post');
    Route::post('/devotee/book_pooja', [BookingController::class, 'storeBooking']);
    Route::get('/devotee/booking/receipt/{id}', [BookingController::class, 'downloadReceipt'])->name('devotee.bookings.receipt');
});

// ============================================
// PRIEST ROUTES (Restricted via RoleMiddleware)
// ============================================
Route::middleware(['auth', 'role.priest'])->group(function () {
    Route::get('/priest/dashboard', [PriestController::class, 'dashboard'])->name('priest.dashboard');
    Route::post('/priest/attendance/toggle', [PriestController::class, 'toggleOnlineStatus'])->name('priest.attendance.toggle');
    Route::post('/priest/attendance/present', [PriestController::class, 'markPresent'])->name('priest.attendance.present');
    Route::post('/priest/attendance/end', [PriestController::class, 'endWork'])->name('priest.attendance.end');
    Route::post('/priest/pooja/complete/{id}', [PriestController::class, 'completePooja'])->name('priest.pooja.complete');
    Route::post('/priest/leave/request', [PriestController::class, 'requestLeave'])->name('priest.leave.request');
});

// ============================================
// TRUSTEE ROUTES (Restricted via RoleMiddleware)
// ============================================
Route::middleware(['auth', 'role.trustee'])->group(function () {
    Route::get('/trustee/dashboard', [TrusteeController::class, 'dashboard'])->name('trustee.dashboard');
});

// ============================================
// STAFF ROUTES (Restricted via RoleMiddleware)
// ============================================
Route::middleware(['auth', 'role.staff'])->group(function () {
    Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');
    Route::post('/staff/attendance/toggle', [StaffController::class, 'toggleOnlineStatus'])->name('staff.attendance.toggle');
    Route::post('/staff/attendance/present', [StaffController::class, 'markPresent'])->name('staff.attendance.present');
    Route::post('/staff/attendance/end', [StaffController::class, 'endWork'])->name('staff.attendance.end');
});

// ============================================
// ACCOUNTANT ROUTES (Restricted via RoleMiddleware)
// ============================================
Route::middleware(['auth', 'role.accountant'])->group(function () {
    Route::get('/accountant/dashboard', [AccountantController::class, 'dashboard'])->name('accountant.dashboard');
});

// ============================================
// COMMON AUTHORIZED AJAX ENDPOINTS
// ============================================
Route::middleware(['auth'])->group(function () {
    // Profile Update Route
    Route::post('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Availability AJAX Endpoints
    Route::get('/booking/check-availability', [BookingController::class, 'checkAvailability'])->name('booking.check-availability');
    Route::get('/booking/check-date-status', [BookingController::class, 'checkDateStatus'])->name('booking.check-date-status');

    // Role Switcher Route removed (role switching inside dashboard is deprecated)

    // Admin & Accountant - Salary Management & Payouts Sanctioning
    Route::get('/admin/salaries', [\App\Http\Controllers\SalaryController::class, 'index'])->name('admin.salaries.index');
    Route::post('/admin/salaries/sanction', [\App\Http\Controllers\SalaryController::class, 'sanction'])->name('admin.salaries.sanction');

    // Admin & Accountant - System Reports Section
    Route::get('/admin/reports', [\App\Http\Controllers\SalaryController::class, 'reports'])->name('admin.reports.index');

    // Admin - Notifications AJAX mark as read
    Route::post('/admin/notifications/mark-read', function () {
        $user = Auth::user();
        if ($user) {
            DB::table('notifications')
                ->where('user_id', $user->id)
                ->update(['is_read' => true, 'updated_at' => now()]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 401);
    })->name('admin.notifications.mark-read');
});