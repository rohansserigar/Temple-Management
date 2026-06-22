<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DevoteeController;
use App\Http\Controllers\PriestController;

// ============================================
// PUBLIC ROUTES
// ============================================
Route::get('/', function () {
    return view('frontend.index');
})->name('home');

// ============================================
// AUTHENTICATION ROUTES
// ============================================
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================
// ADMIN ROUTES (WITHOUT PREFIX FOR SIMPLICITY)
// ============================================
Route::get('/admin/dashboard', function () {
    $devoteesCount = \Illuminate\Support\Facades\DB::table('devotees')->count();
    $priestsCount = \Illuminate\Support\Facades\DB::table('priests')->count();
    
    // Priest status counts
    $onlinePriests = \Illuminate\Support\Facades\DB::table('priests')->where('current_status', 'Online')->count();
    $busyPriests = \Illuminate\Support\Facades\DB::table('priests')->where('current_status', 'Busy')->count();
    $offlinePriests = \Illuminate\Support\Facades\DB::table('priests')->where('current_status', 'Offline')->count();
    $leavePriests = \Illuminate\Support\Facades\DB::table('priests')->where('employment_status', 'On Leave')->count();
    
    // Recent Devotees
    $recentDevotees = \Illuminate\Support\Facades\DB::table('devotees')
        ->join('users', 'devotees.user_id', '=', 'users.id')
        ->select('users.name', 'users.mobile', 'devotees.created_at')
        ->orderBy('devotees.created_at', 'desc')
        ->limit(4)
        ->get();
        
    // Recent Donations
    $recentDonations = \Illuminate\Support\Facades\DB::table('donations')
        ->leftJoin('devotees', 'donations.devotee_id', '=', 'devotees.devotee_id')
        ->leftJoin('users', 'devotees.user_id', '=', 'users.id')
        ->select('users.name as donor_name', 'donations.amount', 'donations.donation_date')
        ->orderBy('donations.donation_date', 'desc')
        ->limit(4)
        ->get();
        
    return view('admin.dashboard', compact(
        'devoteesCount',
        'priestsCount',
        'onlinePriests',
        'busyPriests',
        'offlinePriests',
        'leavePriests',
        'recentDevotees',
        'recentDonations'
    ));
})->name('admin.dashboard');

// Priest Routes
Route::get('/admin/manage-priests', [PriestController::class, 'managePriests'])->name('admin.priests.index');
Route::get('/admin/add-priest', [PriestController::class, 'addPriestPage'])->name('admin.priests.create');
Route::post('/admin/priest/store', [PriestController::class, 'storePriest'])->name('admin.priests.store');
Route::get('/admin/priest/view/{id}', [PriestController::class, 'viewPriest'])->name('admin.priests.view');
Route::get('/admin/priest/edit/{id}', [PriestController::class, 'editPriest'])->name('admin.priests.edit');
Route::post('/admin/priest/update/{id}', [PriestController::class, 'updatePriest'])->name('admin.priests.update');
Route::delete('/admin/priest/delete/{id}', [PriestController::class, 'deletePriest'])->name('admin.priests.delete');

// Devotee Routes
Route::get('/admin/manage-devotees', [DevoteeController::class, 'manageDevotees'])->name('admin.devotees.index');
Route::get('/admin/add-devotee', [DevoteeController::class, 'addDevoteePage'])->name('admin.devotees.create');
Route::post('/admin/devotee/store', [DevoteeController::class, 'storeDevotee'])->name('admin.devotees.store');
Route::post('/admin/devotee/update/{id}', [DevoteeController::class, 'updateDevotee'])->name('admin.devotees.update');
Route::delete('/admin/devotee/delete/{id}', [DevoteeController::class, 'deleteDevotee'])->name('admin.devotees.delete');

// ============================================
// DEVOTEE ROUTES
// ============================================
Route::get('/devotee/dashboard', [DevoteeController::class, 'dashboard'])->name('devotee.dashboard');
Route::get('/devotee/book-pooja', [DevoteeController::class, 'bookPoojaPage'])->name('devotee.book-pooja');
Route::post('/devotee/book-pooja', [DevoteeController::class, 'bookPooja'])->name('devotee.book-pooja.post');

// ============================================
// OTHER USER DASHBOARDS
// ============================================
Route::view('/priest/dashboard', 'priest.dashboard')->name('priest.dashboard');
Route::view('/trustee/dashboard', 'trustee.dashboard')->name('trustee.dashboard');
Route::view('/staff/dashboard', 'staff.dashboard')->name('staff.dashboard');
Route::view('/accountant/dashboard', 'accountant.dashboard')->name('accountant.dashboard');