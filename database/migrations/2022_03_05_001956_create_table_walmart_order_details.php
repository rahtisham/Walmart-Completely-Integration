<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableWalmartOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('walmart_order_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('m_id')->nullable();
            $table->string('purchaseOrderId')->nullable();
            $table->string('customerOrderId')->nullable();
            $table->string('order_date')->nullable();
            $table->string('estimatedDeliveryDate')->nullable();
            $table->string('estimatedShipDate')->nullable();
            $table->string('actualShipDate')->nullable();
            $table->string('actualShipStatus')->nullable();
            $table->string('actualDeliveryDate')->nullable();
            $table->string('actualDeliveryStatus')->nullable();
            $table->string('carrierName')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('status')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('cancellationReason')->nullable();
            $table->string('shippingProgramType')->nullable();
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
        Schema::dropIfExists('walmart_order_details');
    }
}
