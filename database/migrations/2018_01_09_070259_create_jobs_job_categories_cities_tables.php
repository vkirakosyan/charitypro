<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsJobCategoriesCitiesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('img');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('img');
            $table->string('title');
            $table->text('description');
            $table->string('number');
            $table->string('email');
            $table->integer('cat_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->timestamps();
            $table->foreign('cat_id')->references('id')->on('job_categories')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        \DB::statement('alter table job_categories alter column name type varchar;');
        \DB::statement('alter table job_categories alter column img type varchar;');
        \DB::statement('alter table jobs alter column img type varchar;');
        \DB::statement('alter table jobs alter column title type varchar;');
        \DB::statement('alter table jobs alter column number type varchar;');
        \DB::statement('alter table jobs alter column email type varchar;');
        \DB::statement('alter table cities alter column name type varchar;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_categories');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('jobs');
    }
}
