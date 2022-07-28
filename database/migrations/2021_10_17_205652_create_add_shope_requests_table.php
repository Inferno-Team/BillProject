<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddShopeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_shope_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->references('id')->on('user');
            $table->foreignId('admin_id')->nullable()->references('id')->on('user');
            $table->foreignId('shope_id')->nullable()->references('id')->on('shope');
            $table->string('name');
            $table->string('location');
            $table->boolean('approved');
            $table->enum('request_type',['add','edit']);
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
        Schema::dropIfExists('add_shope_requests');
    }
}
