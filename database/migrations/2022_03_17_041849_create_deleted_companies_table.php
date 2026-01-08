<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeletedCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deleted_companies', function (Blueprint $table) {
            $table->id();
            $table->string('ar_name')->index();
            $table->string('slug')->index();
            $table->string('en_name')->index();
            $table->string('email')->index()->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();

            $table->string('website')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('snapchat')->nullable();
            $table->string('linkedin')->nullable();

            $table->text('about')->nullable();
            $table->string('tags',200)->index()->nullable();
            $table->text('phones')->nullable();
            $table->json('location')->nullable();
            $table->string('image')->nullable();

            $table->decimal('average_rate',2,1,true)->default(0);

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order')->nullable(true);
            $table->unsignedInteger('category_id');


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
        Schema::dropIfExists('deleted_companies');
    }
}
