<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddonDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
       public function up()
    {
        Schema::create('addon_devices', function (Blueprint $table) {
            $table->id();
	    $table->string('device_token')->unique()->nullable();
            $table->string('description')->nullable();
            $table->text('notes')->nullable();
	    $table->unsignedBigInteger('device_type_id')->nullable();
	    $table->unsignedBigInteger('main_device_id');
	    $table->unsignedBigInteger('warranty_type_id')->nullable();
	    $table->unsignedBigInteger('vendor_id')->nullable();
	    $table->unsignedBigInteger('manufacturer_id')->nullable();
	    $table->foreign('device_type_id')->references('id')->on('device_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('main_device_id')->references('id')->on('devices')->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->date('buy_date')->nullable();
            $table->string('serial_no')->nullable();
            $table->foreign('warranty_type_id')->references('id')->on('warranties')->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->date('warranty_valid_until')->nullable();
            $table->boolean('active')->nullable();
	    $table->string('order_no')->nullable();
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade')->onUpdate('cascade')->nullable();
	    $table->string('product_no')->nullable();
	    $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('cascade')->onUpdate('cascade')->nullable();
	    $table->json('attachments')->nullable();
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
        Schema::dropIfExists('addon_devices');
    }
}
