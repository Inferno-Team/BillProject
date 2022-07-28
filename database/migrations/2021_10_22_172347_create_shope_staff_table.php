<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopeStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shope_staff', function (Blueprint $table) {
            $table->id();
            $table->enum('postion',['Manager','Cashier']);
            $table->foreignId('shope_id')->references('id')->on('shope');
            $table->foreignId('worker_id')->references('id')->on('user');
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
        Schema::dropIfExists('shope_staff');
    }
}
