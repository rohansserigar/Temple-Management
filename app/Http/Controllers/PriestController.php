<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PriestController extends Controller
{
    public function managePriests()
    {
        $priests = DB::table('priests')
            ->join('users', 'priests.user_id', '=', 'users.id')
            ->select(
                'priests.*',
                'users.name',
                'users.email',
                'users.mobile'
            )
            ->get();

        return view('admin.manage-priests', compact('priests'));
    }

    public function addPriestPage()
    {
        return view('admin.add-priest');
    }

    public function viewPriest($id)
    {
        $priest = DB::table('priests')
            ->join('users', 'priests.user_id', '=', 'users.id')
            ->where('priests.priest_id', $id) // Fixed: Added table name
            ->select(
                'priests.*',
                'users.name',
                'users.email',
                'users.mobile'
            )
            ->first();

        return view('admin.view-priest', compact('priest'));
    }

    public function editPriest($id)
    {
        $priest = DB::table('priests')
            ->join('users', 'priests.user_id', '=', 'users.id')
            ->where('priests.priest_id', $id) // Fixed: Added table name
            ->select(
                'priests.*',
                'users.name',
                'users.email',
                'users.mobile'
            )
            ->first();

        return view('admin.edit-priest', compact('priest'));
    }

    public function updatePriest(Request $request, $id)
    {
        // Add validation
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:10',
            'email' => 'required|email',
            'specialization' => 'nullable|string',
            'salary' => 'required|numeric|min:0',
            'employment_status' => 'required|string',
            'current_status' => 'required|string',
            'joining_date' => 'required|date',
            'address' => 'nullable|string',
            'account_holder_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'ifsc_code' => 'nullable|string|max:11',
            'bank_name' => 'nullable|string',
            'branch_name' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Update Priest
            DB::table('priests')
                ->where('priest_id', $id)
                ->update([
                    'specialization' => $request->specialization,
                    'monthly_salary' => $request->salary,
                    'employment_status' => $request->employment_status,
                    'current_status' => $request->current_status,
                    'joining_date' => $request->joining_date,
                    'address' => $request->address,
                    'account_holder_name' => $request->account_holder_name,
                    'account_number' => $request->account_number,
                    'ifsc_code' => $request->ifsc_code,
                    'bank_name' => $request->bank_name,
                    'branch_name' => $request->branch_name,
                    'updated_at' => now()
                ]);

            // Get user_id from priest table
            $userId = DB::table('priests')
                ->where('priest_id', $id)
                ->value('user_id');

            // Update User
            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'name' => $request->name,
                    'mobile' => $request->mobile,
                    'email' => $request->email,
                    'updated_at' => now()
                ]);

            DB::commit();

            // ========== FIXED: Use named route ==========
            return redirect()->route('admin.priests.index')
                ->with('success', 'Priest Updated Successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update priest: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function deletePriest($id)
    {
        DB::beginTransaction();

        try {
            $priest = DB::table('priests')
                ->where('priest_id', $id)
                ->first();

            if (!$priest) {
                return redirect()->back()->with('error', 'Priest not found.');
            }

            // Delete priest
            DB::table('priests')
                ->where('priest_id', $id)
                ->delete();

            // Delete user
            DB::table('users')
                ->where('id', $priest->user_id)
                ->delete();

            DB::commit();

            // ========== FIXED: Use named route ==========
            return redirect()->route('admin.priests.index')
                ->with('success', 'Priest Deleted Successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete priest: ' . $e->getMessage());
        }
    }

   public function storePriest(Request $request)
{
    // Validate the request - but make email/mobile unique only for users with role 'Priest'
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            function ($attribute, $value, $fail) use ($request) {
                // Check if email exists and user is already a priest
                $existingUser = DB::table('users')
                    ->where('email', $value)
                    ->where('role', 'Priest')
                    ->first();
                
                if ($existingUser) {
                    $fail('This email is already registered as a Priest.');
                }
            }
        ],
        'mobile' => [
            'required',
            'string',
            'max:10',
            function ($attribute, $value, $fail) use ($request) {
                // Check if mobile exists and user is already a priest
                $existingUser = DB::table('users')
                    ->where('mobile', $value)
                    ->where('role', 'Priest')
                    ->first();
                
                if ($existingUser) {
                    $fail('This mobile number is already registered as a Priest.');
                }
            }
        ],
        'gender' => 'nullable|string',
        'dob' => 'nullable|date',
        'experience_years' => 'nullable|integer|min:0|max:50',
        'qualification' => 'nullable|string',
        'emergency_contact' => 'nullable|string|max:10',
        'specialization' => 'required|string',
        'employment_status' => 'nullable|string',
        'current_status' => 'nullable|string',
        'joining_date' => 'required|date',
        'address' => 'nullable|string',
        'monthly_salary' => 'required|numeric|min:0',
        'account_holder_name' => 'nullable|string',
        'account_number' => 'nullable|string',
        'ifsc_code' => 'nullable|string|max:11',
        'bank_name' => 'nullable|string',
        'branch_name' => 'nullable|string',
    ]);

    // Generate 6-digit password
    $password = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    DB::beginTransaction();

    try {
        // Check if user already exists in the system (any role)
        $existingUser = DB::table('users')
            ->where('email', $request->email)
            ->orWhere('mobile', $request->mobile)
            ->first();

        if ($existingUser) {
            // User exists, check if they already have a priest record
            $existingPriest = DB::table('priests')
                ->where('user_id', $existingUser->id)
                ->first();

            if ($existingPriest) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'This user is already registered as a Priest.')
                    ->withInput();
            }

            // User exists but not as priest - update their role and create priest record
            // Update user role to Priest
            DB::table('users')
                ->where('id', $existingUser->id)
                ->update([
                    'role' => 'Priest',
                    'status' => 'Active',
                    'updated_at' => now()
                ]);

            $userId = $existingUser->id;

        } else {
            // User doesn't exist - create new user
            $userId = DB::table('users')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($password),
                'role' => 'Priest',
                'status' => 'Active',
                'must_change_password' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Create Priest record
        DB::table('priests')->insert([
            'user_id' => $userId,
            'priest_id' => 'PRIEST' . str_pad(DB::table('priests')->count() + 1, 4, '0', STR_PAD_LEFT),
            'gender' => $request->gender,
            'dob' => $request->dob,
            'experience_years' => $request->experience_years,
            'qualification' => $request->qualification,
            'emergency_contact' => $request->emergency_contact,
            'specialization' => $request->specialization,
            'employment_status' => $request->employment_status ?? 'Active',
            'current_status' => $request->current_status ?? 'Offline',
            'joining_date' => $request->joining_date,
            'address' => $request->address,
            'monthly_salary' => $request->monthly_salary,
            'account_holder_name' => $request->account_holder_name,
            'account_number' => $request->account_number,
            'ifsc_code' => $request->ifsc_code,
            'bank_name' => $request->bank_name,
            'branch_name' => $request->branch_name,
            'wallet_balance' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::commit();

        // If user was newly created, show password. If existing, don't show password
        $message = $existingUser ? 'User promoted to Priest successfully!' : 'Priest Added Successfully!';
        $passwordMessage = $existingUser ? null : $password;

        return redirect()->route('admin.priests.index')
            ->with('success', $message)
            ->with('generated_password', $passwordMessage);

    } catch (\Exception $e) {
        DB::rollBack();
        
        return redirect()->back()
            ->with('error', 'Failed to add priest: ' . $e->getMessage())
            ->withInput();
    }
}
}