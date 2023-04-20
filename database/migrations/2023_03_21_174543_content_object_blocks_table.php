<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('content_object_blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_object_id');
            $table->unsignedBigInteger('content_object_child_id');
            $table->foreign('content_object_id')->references('id')->on('content_objects')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('content_object_child_id')->references('id')->on('content_objects')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_object_blocks');
    }
};
