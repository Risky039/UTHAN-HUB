<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define Permissions
        $permissions = [
            'manage-users',
            'manage-fees',
            'view-results',
            'manage-results',
            'take-attendance',
            'view-attendance',
            'manage-subjects',
            'manage-classes'
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // This seeder might be run in a tenant context or central context.
        // Assuming tenant context due to usage of `tenant('id')` in previous attempt.
        // If there is no active tenant, we can't create roles with tenant_id.

        $tenantId = null;
        try {
            $tenantId = tenant('id');
        } catch (\Exception $e) {
            // Not in tenant context
        }

        if ($tenantId) {
            // Need a school_id context. Since we don't know which school, we might skip creating roles
            // OR create them for the first school of the tenant.
            $school = \App\Models\School::where('tenant_id', $tenantId)->first();

            if ($school) {
                $roles = [
                    'Admin' => Permission::all()->pluck('id'),
                    'Teacher' => Permission::whereIn('name', ['view-results', 'manage-results', 'take-attendance', 'view-attendance'])->pluck('id'),
                    'Student' => Permission::whereIn('name', ['view-results', 'view-attendance'])->pluck('id'),
                    'Accountant' => Permission::whereIn('name', ['manage-fees'])->pluck('id'),
                ];

                foreach ($roles as $roleName => $permissionIds) {
                    $role = Role::firstOrCreate(
                        ['name' => $roleName, 'school_id' => $school->id, 'tenant_id' => $tenantId],
                        ['teacher_id' => null] // Assuming nullable now
                    );
                    $role->permissions()->sync($permissionIds);
                }
            }
        }
    }
}
