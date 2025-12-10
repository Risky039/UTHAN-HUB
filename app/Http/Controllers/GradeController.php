<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            [
                'message' => "all grades fetched successfully",
                'grades' => Grade::all(),
            ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'level' => 'required|integer|max:255',
            'school_id' => 'required|integer|exists:schools,id',
        ]);
        $tenantId = tenant('id');
        $grade = Grade::create([
            'level' => $request->level,
            'school_id' => $request->school_id,
            'tenant_id' => $tenantId,
        ]);

        return response()->json([
            'message' => 'Grade created successfully',
            'data' => $grade
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        return response()->json([
            'message' => 'Grade retrieved successfully',
            'data' => $grade
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'school_id' => 'sometimes|required|integer|exists:schools,id',
        ]);

        if ($request->has('name')) {
            $grade->name = $request->name;
        }
        if ($request->has('school_id')) {
            $grade->school_id = $request->school_id;
        }
        $grade->save();

        return response()->json([
            'message' => 'Grade updated successfully',
            'data' => $grade
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();

        return response()->json([
            'message' => 'Grade deleted successfully'
        ], 200);
    }
}
