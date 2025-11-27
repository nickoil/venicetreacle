<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('role_id')->default(1)->after('id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->tinyInteger('suspended')->default(0);
            $table->datetime('invited_at')->nullable();
            $table->datetime('first_login_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_login_at');
            $table->dropColumn('invited_at');
            $table->dropColumn('suspended');
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
}