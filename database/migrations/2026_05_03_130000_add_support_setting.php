<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'support'],
            [
                'key'         => 'support',
                'name'        => 'الدعم الفني',
                'description' => '',
                'value'       => null,
                'field'       => '{"name":"value","label":"الدعم الفني","type":"tinymce"}',
                'active'      => 1,
            ]
        );
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'support')->delete();
    }
};
