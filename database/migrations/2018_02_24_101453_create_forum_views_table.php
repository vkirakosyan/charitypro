<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_views', function (Blueprint $table) {
            $table->integer('forum_id')->unsigned();
            $table->timestamps();
            $table->foreign('forum_id')->references('id')->on('forums')->onDelete('cascade');
        });

        \DB::statement('alter table forum_views ADD COLUMN ip inet;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forum_views');
    }
}
