<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopeCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shope_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shope_id')->references('id')->on('shope')->onDelete('cascade');
            $table->string('category_name');
            $table->integer('stock_count');
            $table->foreignId('comp_id')->references('id')->on('companies');
            $table->double('price');
            $table->timestamp('expire_date')->nullable();
            $table->timestamp('createion_date')->nullable();
            $table->string('barcode');
            $table->unique(['barcode', 'shope_id', 'comp_id']);
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
        Schema::dropIfExists('shope_categories');
    }
}
