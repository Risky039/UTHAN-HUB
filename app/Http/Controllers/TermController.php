<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Terms retrieved successfully',
            'data' => Term::all()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Add Authorization Logic
        // For example, assuming specific permission or role checks
        // if (!Auth::user()->can('manage-academic-sessions')) { ... }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'school_id' => 'required|exists:schools,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $term = Term::create([
            'name' => $request->name,
            'academic_session_id' => $request->academic_session_id,
            'school_id' => $request->school_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'tenant_id' => tenant('id'),
        ]);

        return response()->json([
            'message' => 'Term created successfully',
            'data' => $term
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Term $term)
    {
        return response()->json([
            'message' => 'Term retrieved successfully',
            'data' => $term
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Term $term)
    {
        // Authorization Logic
        // if (!Auth::user()->can('manage-academic-sessions')) { return response()->json(['error' => 'Unauthorized'], 403); }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'academic_session_id' => 'sometimes|exists:academic_sessions,id',
            'school_id' => 'sometimes|exists:schools,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'status' => 'sometimes|in:active,inactive',
            'results_released_at' => 'nullable|date',
            'reset_date' => 'nullable|date',
        ]);

        $term->update($request->all());

        return response()->json([
            'message' => 'Term updated successfully',
            'data' => $term
        ], 200);
    }

    /**
     * Mark results as released and set reset date.
     */
    public function releaseResults(Request $request, Term $term)
    {
        // Ensure user has permission
        $user = Auth::user();
        if (!$user) { // || !$user->can('manage-results')
             // Being conservative here if permissions are not fully set up yet,
             // but should block unauthenticated access (handled by middleware)
             // Ideally: Gate::authorize('releaseResults', $term);
        }

        $request->validate([
            'reset_date' => 'required|date|after:now',
        ]);

        $term->update([
            'results_released_at' => now(),
            'reset_date' => $request->reset_date,
        ]);

        return response()->json([
            'message' => 'Results released and reset date set.',
            'term' => $term
        ]);
    }

    /**
     * Get countdown to reset date.
     */
    public function getResetCountdown(Term $term)
    {
        if (!$term->reset_date) {
            return response()->json(['message' => 'Reset date not set for this term.'], 404);
        }

        $resetDate = Carbon::parse($term->reset_date);
        $now = Carbon::now();

        if ($now->gt($resetDate)) {
             return response()->json(['message' => 'System should have reset already.', 'expired' => true]);
        }

        $diff = $now->diff($resetDate);

        return response()->json([
            'days' => $diff->days,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s,
            'reset_date' => $resetDate->toIso8601String(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Term $term)
    {
        $term->delete();

        return response()->json([
            'message' => 'Term deleted successfully'
        ], 200);
    }
}
