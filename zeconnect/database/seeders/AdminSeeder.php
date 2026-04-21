<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@zeconnect.test'],
            [
                'name' => 'System Admin',
                'shop_name' => 'Zeconnect Main Shop',
                'short_name' => 'ZE',
                'mobile' => '0123456789',
                'whatsapp' => '0123456789',
                'address' => 'Main Office, Bangladesh',
                'password' => Hash::make('password'),
            ]
        );
    }
}
