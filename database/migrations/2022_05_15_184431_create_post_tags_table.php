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
        Schema::create('post_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('tag_id');

            $table->foreign('post_id', 'fk-post_tags-post_id')
                ->cascadeOnDelete()
                ->references('id')
                ->on('posts');
                
            $table->foreign('tag_id', 'fk-post_tags-tag_id')
                ->cascadeOnDelete()
                ->references('id')
                ->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_tags', function (Blueprint $table) {
            $table->dropForeign('fk-post_tags-post_id');
            $table->dropForeign('fk-post_tags-tag_id');
        });

        Schema::dropIfExists('post_tags');
    }
};
