<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedInteger('age')->nullable();
            $table->enum('sex', [0, 1, 2])->default(0);
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->boolean('agreed')->default(0);
            $table->string('state')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('adr_drug')->nullable();
            $table->text('other_drugs')->nullable();
            $table->text('adr')->nullable();
            $table->text('risks')->nullable();
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
        Schema::dropIfExists('subscribers');
    }
}
