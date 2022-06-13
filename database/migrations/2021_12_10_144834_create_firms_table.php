<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->string('logo')->nullable();
            $table->string('color')->nullable();
            $table->string('siret')->nullable();

            $table->string('visit_day_1')->nullable();
            $table->string('visit_day_2')->nullable();
            $table->string('time_1')->nullable();
            $table->string('time_2')->nullable();

            $table->string('title')->nullable();
            $table->text('news')->nullable();
            $table->string('image')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firms');
    }
}
