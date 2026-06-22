<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Devotee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|regex:/^[A-Za-z ]+$/',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'gender' => 'required',
            'dob' => 'required|date',
            'password' => 'required|confirmed|min:6',
        ], [
            'name.required' => 'Name is required.',
            'name.regex' => 'Only letters and spaces allowed.',
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email.',
            'email.unique' => 'Email already registered.',
            'mobile.required' => 'Mobile number is required.',
            'mobile.digits' => 'Mobile number must be exactly 10 digits.',
            'mobile.unique' => 'Mobile number already registered.',
            'gender.required' => 'Please select gender.',
            'dob.required' => 'Date of birth is required.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'role' => 'Devotee',
            'status' => 'Active'
        ]);

        Devotee::create([
            'user_id' => $user->id,
            'address' => $request->address,
            'gothra' => $request->gothra,
            'nakshatra' => $request->nakshatra,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'verified' => 0,
        ]);

        return redirect()->route('login')
            ->with('success', 'Registration Successful. Please Login.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required'
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists
        if (!$user) {
            return back()
                ->withErrors(['email' => 'No account found with this email.'])
                ->withInput();
        }

        // Check password
        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['password' => 'Incorrect password.'])
                ->withInput();
        }

        // Check role matches (except for Devotee - anyone can login as Devotee)
        if ($request->role != 'Devotee') {
            if ($user->role !== $request->role) {
                return back()
                    ->withErrors(['role' => 'This account is not registered as ' . $request->role . '.'])
                    ->withInput();
            }
        }

        // Login the user
        Auth::login($user);

        // Redirect based on selected role
        switch ($request->role) {
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Logged out successfully.');
    }
}