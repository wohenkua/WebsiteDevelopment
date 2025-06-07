<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 初步使用图片信息的迁移文件
     * 该表用于存储图片的基本信息，如名称、路径、大小等
     * 目前仅作为临时表，后续可能会根据实际需求进行修改
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // TODO: temporary migration for images table, to be modified later
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('user_id')->constrained()->onDelete('cascade'); //
            $table->string('name')->unique();
            $table->string('original_name')->nullable();
            $table->string('path')->unique();
            $table->string('url')->unique();
            $table->unsignedBigInteger('size');
            $table->string('mime_type');
            $table->timestamp('last_modified')->nullable();

            $table->timestamps();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
