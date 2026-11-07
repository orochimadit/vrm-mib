<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'username' => 'admin_mcm',
                'name' => 'Administrator MCM',
                'email' => 'admin@vesselriskmanagement.com',
                'role' => 'admin_mcm',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ],
            [
                'username' => 'gaic',
                'name' => 'GREAT AMERICAN INSURANCE COMPANY',
                'email' => 'underwriter@vesselriskmanagement.com',
                'role' => 'insurance',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ],
            [
                'username' => 'mcm123',
                'name' => 'Surveyor MCM Lapangan',
                'email' => 'surveyor@vesselriskmanagement.com',
                'role' => 'surveyor',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ],
            [
                'username' => 'pelayaransariampenan',
                'name' => 'PT. Pelayaran Sari Ampenan (Shipowner)',
                'email' => 'shipowner@vesselriskmanagement.com',
                'role' => 'shipowner',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ],
            [
                'username' => 'lockton',
                'name' => 'LOCKTON BROKER PARTNER',
                'email' => 'broker@vesselriskmanagement.com',
                'role' => 'broker',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ],
        ];

        foreach ($accounts as $data) {
            $user = User::where('username', $data['username'])
                ->orWhere('email', $data['email'])
                ->first();

            if ($user) {
                $user->update($data);
                $this->command->info("Updated test account: {$data['username']} [{$data['role']}]");
            } else {
                User::create($data);
                $this->command->info("Created test account: {$data['username']} [{$data['role']}]");
            }
        }
    }
}
