<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'email' => 'required|email|unique:schools,email',
            'phone' => 'required|string|max:20',
            'domain' => 'required|string|unique:schools,domain',
            'logo' => 'nullable|string|max:2048',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:admins,email',
            'admin_password' => 'required|string|min:6',
            'admin_phone' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();

        try {

              $tenant = \App\Models\Tenant::create([
                'id' => $request->domain,  
                'data' => [
                ],
            ]);

            //  Create School
            $schoolData = $request->only(['name', 'address', 'email', 'phone', 'domain','tenant_id']);
            $schoolData['tenant_id'] = $tenant->id;
            // Default logo if not provided?
             $schoolData['logo'] = $request->filled('logo') ? $request->logo : 'default_logo.png';

            if ($request->filled('logo')) {
                $schoolData['logo'] = $request->logo;
            }
            $school = \App\Models\School::create($schoolData);

          
            // Create Domain
            $tenant->domains()->create([
                'domain' => $request->domain . '.utanhub.test',
            ]);

            //  Create Admin
            $admin = \App\Models\Admin::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'phone' => $request->admin_phone,
                'school_id' => $school->id,
                'password' => bcrypt($request->admin_password),
                'tenant_id' => $tenant->id,
            ]);

            //  Generate API token
            $token = $admin->createToken('admin-token')->plainTextToken;

            DB::commit();

            return response()->json([
                'message' => 'School, tenant, domain and admin created successfully',
                'school' => $school,
                'tenant' => $tenant,
                'admin' => $admin,
                'token' => $token,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong. All changes have been rolled back.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update school settings including term fee.
     */
    public function updateSettings(Request $request, $id)
    {
        // Add Authorization Logic
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $school = \App\Models\School::findOrFail($id);

        // Check if user belongs to this school and has permission
        // Simplified check:
        if ($user->school_id !== $school->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'term_fee' => 'nullable|numeric|min:0',
            // other settings...
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('term_fee')) {
            $school->term_fee = $request->term_fee;
        }

        $school->save();

        return response()->json(['message' => 'School settings updated', 'school' => $school]);
    }
}
