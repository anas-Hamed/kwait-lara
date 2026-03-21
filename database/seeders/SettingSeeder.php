<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'key'         => 'google_play',
                'name'        => 'رابط التطبيق في متجر غوغل',
                'description' => '',
                'value'       => '{"ar":"https:\/\/play.google.com\/store\/apps\/details?id=com.facebook.katana&hl=ar&gl=US"}',
                'field'       => '{"name":"value","label":"رابط التطبيق","type":"url"}',
                'active'      => 1,
            ],
            [
                'key'         => 'apple_store',
                'name'        => 'رابط التطبيق في متجر أبل',
                'description' => '',
                'value'       => '{"ar":"https:\/\/play.google.com\/store\/apps\/details?id=com.facebook.katana&hl=ar&gl=US"}',
                'field'       => '{"name":"value","label":"رابط التطبيق","type":"url"}',
                'active'      => 1,
            ],
            [
                'key'         => 'email',
                'name'        => 'البريد الإلكتروني',
                'description' => '',
                'value'       => '{"ar":"info@kuwaitexplorer.com"}',
                'field'       => '{"name":"value","label":"البريد الإلكتروني","type":"email"}',
                'active'      => 1,
            ],
            [
                'key'           => 'phone',
                'name'          => 'الهاتف',
                'description'   => '',
                'value'       => '{"ar":"+965555552222"}',
                'field'         => '{"name":"value","label":"الهاتف","type":"text"}',
                'active'        => 1,
            ],
            [
                'key'           => 'website',
                'name'          => 'الموقع الالكتروني',
                'description'   => '',
                'value'       => '{"ar":"https:\/\/kuwaitexplorer.com"}',
                'field'         => '{"name":"value","label":"الرابط","type":"url"}',
                'active'        => 1,
            ],
            [
                'key'           => 'facebook',
                'name'          => 'فيسبوك',
                'description'   => '',
                'value'       => '{"ar":"https:\/\/kuwaitexplorer.com"}',
                'field'         => '{"name":"value","label":"الرابط","type":"url"}',
                'active'        => 1,
            ],
            [
                'key'           => 'twitter',
                'name'          => 'تويتر',
                'description'   => '',
                'value'       => '{"ar":"https:\/\/kuwaitexplorer.com"}',
                'field'         => '{"name":"value","label":"الرابط","type":"url"}',
                'active'        => 1,
            ],
            [
                'key'           => 'youtube',
                'name'          => 'يوتيوب',
                'description'   => '',
                'value'       => '{"ar":"https:\/\/kuwaitexplorer.com"}',
                'field'         => '{"name":"value","label":"الرابط","type":"url"}',
                'active'        => 1,
            ],
            [
                'key'           => 'whatsapp',
                'name'          => 'واتساب',
                'description'   => '',
                'value'       => '{"ar":"+965555552222"}',
                'field'         => '{"name":"value","label":"واتساب","type":"text"}',
                'active'        => 1,
            ],
            [
                'key'           => 'about_us',
                'name'          => 'من نحن',
                'description'   => '',
                'value'         => null,
                'field'         => '{"name":"value","label":"من نحن","type":"tinymce"}',
                'active'        => 1,
            ],
            [
                'key'           => 'privacy',
                'name'          => 'سياسة الخصوصية',
                'description'   => '',
                'value'         => null,
                'field'         => '{"name":"value","label":"سياسة الخصوصية","type":"tinymce"}',
                'active'        => 1,
            ],
            [
                'key'           => 'terms',
                'name'          => 'شروط الاستخدام',
                'description'   => '',
                'value'         => null,
                'field'         => '{"name":"value","label":"شروط الاستخدام","type":"tinymce"}',
                'active'        => 1,
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
