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
        Schema::create('sidebar_widget', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sidebar_id');
            $table->unsignedBigInteger('widget_id');
            $table->foreign('sidebar_id')->references('id')->on('sidebars')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('widget_id')->references('id')->on('widgets')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sidebar_widget');
    }
};
