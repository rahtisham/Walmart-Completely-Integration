<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressColum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usersrs', function (Blueprint $table) {
            $table->string('contact')->after('last_name')->nullable();
            $table->string('state')->after('address')->nullable();
            $table->string('city')->after('state')->nullable();
            $table->string('postal')->after('country')->nullable();
            $table->string('country')->after('city')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usersrs', function (Blueprint $table) {
            //
        });
    }
}
