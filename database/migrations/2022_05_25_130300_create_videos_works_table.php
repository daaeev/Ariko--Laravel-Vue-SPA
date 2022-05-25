<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos_works', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('subject', 50);
            $table->string('year', 10);
            $table->string('video');
            $table->string('client', 50)->nullable();
            $table->string('website')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos_works');
    }
};
