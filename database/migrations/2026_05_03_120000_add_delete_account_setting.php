<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'delete_account'],
            [
                'key'         => 'delete_account',
                'name'        => 'حذف الحساب',
                'description' => '',
                'value'       => null,
                'field'       => '{"name":"value","label":"حذف الحساب","type":"tinymce"}',
                'active'      => 1,
            ]
        );
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'delete_account')->delete();
    }
};
