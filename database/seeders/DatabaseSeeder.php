<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@system.com',
            'no_hp' => '081234567890',
            'password' => Hash::make('admin123'),
            'role' => 'Admin'
        ]);

        User::create([
            'nama' => 'Staff Produksi',
            'username' => 'staff',
            'email' => 'staff@system.com',
            'no_hp' => '081234567891',
            'password' => Hash::make('staff123'),
            'role' => 'Staff'
        ]);
    }
}