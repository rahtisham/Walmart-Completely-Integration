<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Integrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('platform')->nullable();
            $table->string('name')->nullable();
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();;
            $table->string('is_active')->nullable();;
            $table->string('token')->nullable();;
            $table->string('status')->nullable();
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
        //
        Schema::dropIfExists('integrations');
    }
}
