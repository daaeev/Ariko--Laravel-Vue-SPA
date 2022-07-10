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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->unsignedBigInteger('photo_work_id');

            $table->foreign(
                'photo_work_id',
                'fk-images-photo_work_id'
            )
                ->cascadeOnDelete()
                ->references('id')
                ->on('photos_works');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropForeign('fk-images-photo_work_id');
            $table->dropIndex('fk-images-photo_work_id');
        });

        Schema::dropIfExists('images');
    }
};
