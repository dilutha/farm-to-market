<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdiminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'newadmin123@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('chanith123'),
                'role' => 'Admin',
                'contact_no' => '0770000000',
                'status' => 'Active',
            ]
        );
    }
}
