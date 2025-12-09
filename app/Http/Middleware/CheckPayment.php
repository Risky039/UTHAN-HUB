<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Support\Facades\Auth;

class CheckPayment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // This middleware assumes the user is authenticated and is a Student.
        // If the user is an Admin or Teacher, they might bypass this check.

        // We need a way to identify if the user is a student.
        // Assuming there is a way (e.g., separate guard or type check).
        // Since the current Auth system seems to use Sanctum and multiple tables (Admin, Student, Teacher),
        // we can check the instance type of the user.

        if ($user instanceof Student) {
             // Find current active term for the student's school
             $term = Term::where('school_id', $user->school_id)
                 ->where('status', 'active')
                 ->first();

             if ($term) {
                 // Check if payment exists and is paid
                 $payment = Payment::where('student_id', $user->id)
                     ->where('term_id', $term->id)
                     ->where('status', 'paid')
                     ->first();

                 if (!$payment) {
                     // Get School Fee
                     $school = $user->school;
                     if ($school && $school->term_fee > 0) {
                         return response()->json([
                             'message' => 'Access denied. You must pay the term fee first.',
                             'required_fee' => $school->term_fee,
                             'term' => $term->name
                         ], 403);
                     }
                 }
             }
        }

        return $next($request);
    }
}
