<?php

namespace Database\Seeders;

use App\Models\User;
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
//        User::query()->insert([
//            'name' => 'admin',
//            'email' => 'admin@gmail.com',
//            'password' => Hash::make(123123),
//            'is_active' => 1,
//            'is_admin' => 1,
//            'verified_at' => now(),
//            'phone' => '00905318402220',
//
//        ]);

        User::factory(10)->create();
    }
}
