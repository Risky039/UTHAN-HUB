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
        Schema::create('students', function (Blueprint $table) {
            $table->id('id');
            $table->string('username')->unique();
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique()->nullable();
            $table->string("password");
            $table->string('phone')->unique()->nullable();
            $table->string('address');
            $table->string('passport')->nullable();
            $table->string('bloodType');
            $table->string('sex');
            $table->unsignedBigInteger('classId');
            $table->unsignedBigInteger('gradeId');
            $table->date('birthday');
            $table->string('guardianName');
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('tenant_id');
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');
            $table->foreign('classId')->references('id')->on('class_rooms')->onDelete('cascade');
            $table->foreign('gradeId')->references('id')->on('grades')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
