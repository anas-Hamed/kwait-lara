<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeletedWorkTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deleted_work_times', function (Blueprint $table) {
            $table->id();
            $table->integer('day');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('active')->default(0);
            $table->unsignedBigInteger('deleted_company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deleted_work_times');
    }
}
