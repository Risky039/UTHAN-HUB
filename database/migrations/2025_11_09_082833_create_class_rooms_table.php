<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('capacity');

            $table->unsignedBigInteger('grade_id');

            $table->foreignId('school_id')->constrained()->onDelete('cascade'); // creates column + FK
             $table->string('tenant_id'); // same type as the primary key in tenants
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');
            $table->unsignedBigInteger('form_teacher')->nullable();
            $table->foreign('form_teacher')->references('id')->on('teachers')->onDelete('set null');
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_rooms');
    }
};
