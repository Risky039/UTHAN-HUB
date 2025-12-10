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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
              $table->string('name');
            $table->enum('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->timestamp('startTime')->nullable();
            $table->timestamp('endTime')->nullable();

            $table->unsignedBigInteger('subjectId'); 
            $table->unsignedBigInteger('classId');   
            $table->unsignedBigInteger('teacherId');
            $table->unsignedBigInteger('school_id');               

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('subjectId')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('classId')->references('id')->on('class_rooms')->onDelete('cascade');
            $table->foreign('teacherId')->references('id')->on('teachers')->onDelete('cascade');
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
        Schema::dropIfExists('lessons');
    }
};
