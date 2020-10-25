<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranscodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transcode', function (Blueprint $table) {
            $table->id();
            $table->string('original_file_name', 255);
            $table->integer('original_file_size');
            $table->string('transcoder', 100);
            $table->integer('bitrate');
            $table->string('resolution', 100);
            $table->string('compressed_file_name', 255)->nullable();
            $table->integer('compressed_file_size')->nullable();
            $table->timestamps();
            $table->timestamp('completed_at', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transcode');
    }
}
