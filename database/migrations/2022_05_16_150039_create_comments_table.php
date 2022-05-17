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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('email', 254);
            $table->text('comment');
            $table->boolean('checked')->default(false);
            $table->unsignedBigInteger('post_id');
            $table->timestamps();

            $table->foreign('post_id', 'fk-comments-post_id')
                ->cascadeOnDelete()
                ->references('id')
                ->on('posts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('fk-comments-post_id');
        });

        Schema::dropIfExists('comments');
    }
};
