<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('status', ['en attente', 'en cours', 'terminee'])->default('en attente');
            $table->float('total')->nullable();
            $table->string('comments')->nullable();
            $table->string('note_admin')->nullable();
            $table->integer('user_id')->foreign()
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->integer('firm_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
