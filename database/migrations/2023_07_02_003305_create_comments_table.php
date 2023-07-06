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
        Schema::create('comments', function (Blueprint $table) {
            $table->integer('comment_id')->primary();
            $table->integer('issue_id');
            $table->text('comment');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });

        // Set foreign keys
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('issue_id')->references('issue_id')->on('issues');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
