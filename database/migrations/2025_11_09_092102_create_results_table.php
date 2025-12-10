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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->integer('score');
            $table->string('grade')->nullable();
            $table->string('remarks')->nullable();                           

            $table->unsignedBigInteger('examId')->nullable();       
            $table->unsignedBigInteger('assignmentId')->nullable(); 
            $table->unsignedBigInteger('studentId');   
            $table->unsignedBigInteger('academicSessionId');
            $table->unsignedBigInteger('termId')->nullable();
            $table->unsignedBigInteger('school_id');

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('examId')->references('id')->on('exams')->onDelete('set null');
            $table->foreign('academicSessionId')->references('id')->on('academic_sessions')->onDelete('cascade');
            $table->foreign('termId')->references('id')->on('terms')->onDelete('set null');
            $table->foreign('assignmentId')->references('id')->on('assignments')->onDelete('set null');
 $table->string('tenant_id'); // same type as the primary key in tenants
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreign('studentId')->references('id')->on('students')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
