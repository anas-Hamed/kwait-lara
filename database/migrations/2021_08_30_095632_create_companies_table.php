<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('companies', function (Blueprint $table) {

            $table->id();
            $table->string('ar_name')->index();
            $table->string('slug')->index();
            $table->string('en_name')->index();
            $table->string('email')->index();
            $table->string('phone');
            $table->string('whatsapp');

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
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('has_paid')->default(false);
            $table->decimal('average_rate',2,1,true)->default(0);

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order')->nullable(true);
            $table->unsignedInteger('category_id');


            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
