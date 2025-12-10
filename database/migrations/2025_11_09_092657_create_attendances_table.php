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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
             $table->timestamp('date');      
            $table->boolean('present');     

            $table->unsignedBigInteger('studentId');       
            $table->unsignedBigInteger('lessonId'); 
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('classRoomId');
            $table->foreign('classRoomId')->references('id')->on('class_rooms')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('studentId')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('lessonId')->references('id')->on('lessons')->onDelete('cascade');
            $table->unsignedBigInteger('academicSessionId');
            $table->foreign('academicSessionId')->references('id')->on('academic_sessions')->onDelete('cascade');
            $table->unsignedBigInteger('termId')->nullable();
            $table->foreign('termId')->references('id')->on('terms')->onDelete('set null');
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
        Schema::dropIfExists('attendances');
    }
};
