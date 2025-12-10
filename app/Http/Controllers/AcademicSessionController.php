<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use Illuminate\Http\Request;

class AcademicSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Academic sessions retrieved successfully',
            'data' => AcademicSession::all()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'school_id' => 'required|integer|exists:schools,id',
            'status' => 'nullable|in:active,inactive',
        ]);

        $academicSession = AcademicSession::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'school_id' => $request->school_id,
            'status' => $request->status ?? 'inactive',
            'tenant_id' => tenant('id'),
        ]);

        return response()->json([
            'message' => 'Academic session created successfully',
            'data' => $academicSession
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicSession $academicSession)
    {
        return response()->json([
            'message' => 'Academic session retrieved successfully',
            'data' => $academicSession
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicSession $academicSession)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'school_id' => 'sometimes|required|integer|exists:schools,id',
            'status' => 'sometimes|nullable|in:active,inactive',
        ]);

        $academicSession->update($request->only(['name', 'start_date', 'end_date', 'school_id', 'status']));

        return response()->json([
            'message' => 'Academic session updated successfully',
            'data' => $academicSession
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicSession $academicSession)
    {
        $academicSession->delete();

        return response()->json([
            'message' => 'Academic session deleted successfully'
        ], 200);
    }
}
