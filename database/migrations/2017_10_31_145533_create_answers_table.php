<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();           //回覆用戶ID
            $table->unsignedInteger('archive_id')->index();        //回覆文章ID
            $table->text('body');                                  //回覆內文
            $table->unsignedInteger('votes_count')->default(0);    //讚數
            $table->unsignedInteger('comments_count')->default(0); //留言數
            $table->boolean('is_hidden')->default(false);          //是否隱藏
            $table->boolean('close_comment')->default(false);      //是否關閉評論
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
        Schema::dropIfExists('answers');
    }
}
