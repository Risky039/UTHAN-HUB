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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
             $table->string('title');
             $table->integer('score');
            $table->unsignedBigInteger('lessonId');
            $table->unsignedBigInteger('academicSessionId');
            $table->foreign('academicSessionId')->references('id')->on('academic_sessions')->onDelete('cascade');
            $table->unsignedBigInteger('termId')->nullable();
            $table->foreign('termId')->references('id')->on('terms')->onDelete('set null');
            $table->unsignedBigInteger('school_id');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('lessonId')->references('id')->on('lessons')->onDelete('cascade');
             $table->string('tenant_id'); // same type as the primary key in tenants
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
