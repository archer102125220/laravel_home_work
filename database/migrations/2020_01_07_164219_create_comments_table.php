<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('comment_id')->unsigned();
            $table->biginteger('posts_id')->unsigned();
            $table->string('account',20);
            $table->string('content',60);
            $table->dateTime('created_at');
            $table->foreign('account')->references('account')->on('users')->onDelete('cascade');
            $table->foreign('posts_id')->references('posts_id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
