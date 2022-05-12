<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('odetails', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('order_id')->foreign()->references('id')->on('orders')->onDelete('cascade');
            $table->enum('status', ['indisponible', 'disponible'])->default('disponible');
            $table->integer('product_id')->foreign()->references('id')->on('products');
            $table->float('price_product')->nullable();
            $table->integer('qtty')->default('1');
            $table->string('name')->nullable();
            $table->string('comments')->nullable();
            $table->float('total_odetail')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('odetails');
    }
}
