<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'key'           => 'snapchat',
                'name'          => 'سناب شات',
                'description'   => '',
                'value'       => '{"ar":"https:\/\/kuwaitexplorer.com"}',
                'field'         => '{"name":"value","label":"الرابط","type":"url"}',
                'active'        => 1,
            ],
            [
                'key'           => 'instagram',
                'name'          => 'انستغرام',
                'description'   => '',
                'value'       => '{"ar":"https:\/\/kuwaitexplorer.com"}',
                'field'         => '{"name":"value","label":"الرابط","type":"url"}',
                'active'        => 1,
            ],
            [
                'key'           => 'linkedin',
                'name'          => 'لينكد ان',
                'description'   => '',
                'value'       => '{"ar":"https:\/\/kuwaitexplorer.com"}',
                'field'         => '{"name":"value","label":"الرابط","type":"url"}',
                'active'        => 1,
            ],
        ]);
    }
}
