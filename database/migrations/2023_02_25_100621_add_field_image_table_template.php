<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldImageTableTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('template_messages', function (Blueprint $table) {
            $table->text('url')->nullable();
            $table->string('size')->nullable();
            $table->string('mime')->nullable();
            $table->string('path')->nullable();
            $table->string('filename')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('template_messages', function (Blueprint $table) {
            $table->dropColumn([
                'url',
                'size',
                'mime',
                'path',
                'filename'
            ]);
        });
    }
}
