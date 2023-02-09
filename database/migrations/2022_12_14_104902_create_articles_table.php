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
        Schema::create('articles', function (Blueprint $table) {
            $table->string('id');
            $table->primary('id');
            $table->timestamps();
            $table->string('title');
            $table->text('draft_content');
            $table->dateTime('draft_last_update')->nullable();
            $table->boolean('is_published')->default(false);
            $table->text('publication_content')->nullable();
            $table->dateTime('publication_last_update')->nullable();
            $table->string('slug');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
