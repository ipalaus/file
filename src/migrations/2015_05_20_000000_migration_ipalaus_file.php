<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MigrationIpalausFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('byte_size')->unsigned();
            $table->string('storage_engine', 32);
            $table->string('storage_format', 32);
            $table->string('storage_handle')->index();
            $table->string('secret')->index();
            $table->string('content_hash', 40);
            $table->longText('meta_data');
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->boolean('is_explicit_upload')->default(0);
            $table->boolean('is_transformed')->default(0);
            $table->timestamps();
        });

        Schema::create('file_transformations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('original_id')->unsigned()->index();
            $table->integer('transformed_id')->unsigned()->index();
            $table->string('transform')->index();
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
        Schema::drop('files');
        Schema::drop('file_transformations');
    }
}
