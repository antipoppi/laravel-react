<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
	    $table->string('device_token')->unique();
            $table->string('admin')->nullable();
            $table->string('admin_password')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('user_id');
	    $table->unsignedBigInteger('operating_system_id');
            $table->unsignedBigInteger('device_type_id');
            $table->unsignedBigInteger('license_id')->nullable();
            $table->unsignedBigInteger('software_id')->nullable();
            $table->unsignedBigInteger('warranty_id')->nullable();
            $table->unsignedBigInteger('manufacturer_id');
            $table->unsignedBigInteger('security_software_id')->nullable();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('backup_solution_id')->nullable();
            $table->string('device_name');
            $table->string('device_model');
	    $table->text('notes')->nullable();
            $table->date('buy_date');
            $table->string('serial_no');
            $table->date('warranty_valid_until')->nullable();
	    $table->string('teamviewer_id');
            $table->boolean('active');
	    $table->string('order_no');
	    $table->string('product_no');
	    $table->string('lease_contract_no')->nullable();
	    $table->json('attachments')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
	    $table->foreign('operating_system_id')->references('id')->on('manufacturers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('license_id')->references('id')->on('licenses')->onDelete('cascade')->onUpdate('cascade')->nullable();
	    $table->foreign('software_id')->references('id')->on('softwares')->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->foreign('device_type_id')->references('id')->on('device_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('warranty_id')->references('id')->on('warranties')->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->foreign('security_software_id')->references('id')->on('security_softwares')->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade')->onUpdate('cascade');
	    $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('cascade')->onUpdate('cascade');
	    $table->foreign('backup_solution_id')->references('id')->on('backup_solutions')->onDelete('cascade')->onUpdate('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device');
    }
}
