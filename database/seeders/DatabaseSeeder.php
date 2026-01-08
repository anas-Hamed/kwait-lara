<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyTag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::query()->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make(123123),
            'is_admin' => 1,
            'email_verified_at' => now(),
            'phone' => '99999999',

        ]);
        User::query()->insert([
            'name' => 'agent',
            'email' => 'agent@gmail.com',
            'password' => Hash::make(123123),
            'is_admin' => 0,
            'email_verified_at' => now(),
            'phone' => '7777',

        ]);

        $this->call(SettingSeeder::class);
        $this->call(CategorySeeder::class);
//        $this->call(CompanySeeder::class);


    }
}
