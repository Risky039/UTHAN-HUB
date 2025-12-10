<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(ClassRoom::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'grade_id' => 'required|integer|exists:grades,id',
            'form_teacher' => 'nullable|integer|exists:teachers,id',
            'school_id' => 'required|integer|exists:schools,id',
        ]);
        $classRoom = ClassRoom::create([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'grade_id' => $request->grade_id,
            'form_teacher' => $request->form_teacher,
            'school_id' => $request->school_id,
            'tenant_id' => tenant('id'),
        ]);
        return response()->json(
            ['classroom' => $classRoom, 201]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassRoom $classRoom)
    {
        return response()->json($classRoom);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassRoom $classRoom)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'grade_id' => 'sometimes|required|integer|exists:grades,id',
            'form_teacher' => 'sometimes|nullable|integer|exists:teachers,id',
            'school_id' => 'sometimes|required|integer|exists:schools,id',
        ]);
        $classRoom->update($request->all());
        return response()->json($classRoom);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassRoom $classRoom)
    {
        $classRoom->delete();
        return response()->json(['message' => "Classroom deleted successfully", 204]);
    }
}
