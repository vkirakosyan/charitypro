<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'provider')) {
            $table->string('provider')->nullable();
        }

        if (!Schema::hasColumn('users', 'provider_id')) {
            $table->string('provider_id')->nullable();
        }

        if (Schema::hasColumn('users', 'email')) {
          $table->dropUnique('users_email_unique');
          $table->string('email')->nullable()->change();
        }

        if (Schema::hasColumn('users', 'password')) {
          $table->string('password')->nullable()->change();
        }
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'provider')) {
            $table->dropColumn('provider');
        }

        if (Schema::hasColumn('users', 'provider_id')) {
            $table->dropColumn('provider_id');
        }

        if (Schema::hasColumn('users', 'email')) {
          $table->string('email')->nullable(false)->unique()->change();
        }

        if (Schema::hasColumn('users', 'password')) {
          $table->string('password')->nullable(false)->change();
        }
      });
    }
}
