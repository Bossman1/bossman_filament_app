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
        Schema::create('content_objects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->longText('content');
            $table->boolean('is_published')->default(false);
            $table->integer('layout_id');
            $table->integer('template_id');
            $table->integer('relation_view_id');
            $table->bigInteger('content_object_id');
            $table->bigInteger('sidebar_id');
            $table->boolean('show_in_menu')->default(0);
            $table->integer('user_id')->nullable();
            $table->bigInteger('sort')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_objects');
    }
};
