<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
 public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
                //$table->bigIncrements('id');
                $table->id();
                $table->string('customer_token')->unique();
                $table->string('name')->unique();
		$table->string('address');
                $table->string('contact_person_name')->nullable();
		$table->boolean('active');
		$table->text('notes')->nullable();
		//$table->foreign('contact_person_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('customers');
    }
}
