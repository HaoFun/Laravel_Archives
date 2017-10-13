<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');                               //文章標題
            $table->text('body');                                  //文章內容
            $table->unsignedInteger('user_id');                    //文章作者
            $table->unsignedInteger('comments_count')->default(0); //文章評論數
            $table->unsignedInteger('followers_count')->default(0);//文章關注數
            $table->unsignedInteger('answers_count')->default(0);  //文章回覆數
            $table->boolean('close_comment')->default(false);      //是否關閉評論
            $table->boolean('is_hidden')->default(false);          //是否顯示
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
        Schema::dropIfExists('archives');
    }
}
