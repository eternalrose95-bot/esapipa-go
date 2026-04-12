<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::find(1); // Super Administrator
        if ($role) {
            $role->permissions = config('permissions.permissions');
            $role->save();
        }
    }
}