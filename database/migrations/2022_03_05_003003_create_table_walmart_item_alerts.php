<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableWalmartItemAlerts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('walmart_item_alerts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('m_id')->nullable();
            $table->string('sku')->nullable();
            $table->string('wpid')->nullable();
            $table->string('product_name')->nullable();
            $table->longText('reason')->nullable();
            $table->string('status')->nullable();
            $table->string('alert_type')->nullable();
            $table->string('product_url')->nullable();
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
        Schema::dropIfExists('walmart_item_alerts');
    }
}
