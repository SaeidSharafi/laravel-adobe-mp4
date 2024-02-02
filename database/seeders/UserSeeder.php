<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'first_name' => 'admin',
            'last_name'  => '',
            'phone'      => '09111111111',
            'email'      => 'admin@easy.ir',
            'password'   => Hash::make("123456"),
            'is_admin'   => true
        ]);

        $user = User::factory()->create([
            'first_name' => 'manager',
            'last_name'  => '',
            'phone'      => '09222222222',
            'email'      => 'manager@easy.ir',
            'password'   => Hash::make("123456"),
        ]);
        $user->assignRole('manager');
    }
}
