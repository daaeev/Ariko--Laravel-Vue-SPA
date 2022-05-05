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
        Schema::create('photos_works', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('subject', 50);
            $table->string('year', 10);
            $table->string('client', 50)->nullable();
            $table->string('website')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('preview_image_id')->unique();

            $table->foreign(
                'preview_image_id',
                'fk-photos_works-preview_image_id'
            )
                ->cascadeOnDelete()
                ->references('id')
                ->on('images');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('photos_works', function (Blueprint $table) {
            $table->dropForeign('fk-photos_works-preview_image_id');
        });

        Schema::dropIfExists('photos');
    }
};
