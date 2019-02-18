<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned(); // pertenece a un usuario
             $table->integer('category_id')->unsigned(); // pertenece a una categoria

             $table->string('name', 128);
             $table->string('slug', 128)->unique();
             $table->mediumText('excerpt')->nullable();
             $table->text('body');
             $table->enum('status', ['PUBLISHED','DRAFT'])->default('DRAFT'); //PUBLICADO / BORRADOR

             $table->string('file', 128)->nullable(); // imagen puede tener o no---

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade'); // si eliminamos un usuario se van a eliminar todos los post del usuario

            $table->foreign('category_id')->references('id')->on('categories')
            ->onDelete('cascade')
            ->onUpdate('cascade'); // si eliminamos una categoria se van a eliminar todos los post de esa categoria

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
