<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->float('price')->default(10.00)->nullable();
            $table->enum('status', ['actif', 'inactif'])->default('actif');
            $table->integer("service_id")->foreign()->references('id')->on('services')->onDelete('cascade')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
