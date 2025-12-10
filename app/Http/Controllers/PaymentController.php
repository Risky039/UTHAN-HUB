<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\School;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Record a payment for a student.
     * Accessible by Admin or Accountant.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'term_id' => 'required|exists:terms,id',
            'amount' => 'required|numeric|min:0',
            'transaction_reference' => 'nullable|string',
            'status' => 'required|in:pending,paid,failed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if payment already exists for this term and student
        $existingPayment = Payment::where('student_id', $request->student_id)
            ->where('term_id', $request->term_id)
            ->first();

        if ($existingPayment) {
            $existingPayment->update($request->only(['amount', 'status', 'transaction_reference']));
            return response()->json(['message' => 'Payment updated successfully', 'payment' => $existingPayment]);
        }

        // Get school_id and tenant_id from the student or context
        $student = Student::findOrFail($request->student_id);

        $payment = Payment::create([
            'student_id' => $request->student_id,
            'term_id' => $request->term_id,
            'amount' => $request->amount,
            'status' => $request->status,
            'transaction_reference' => $request->transaction_reference,
            'school_id' => $student->school_id,
            'tenant_id' => $student->tenant_id,
        ]);

        return response()->json(['message' => 'Payment recorded successfully', 'payment' => $payment], 201);
    }

    /**
     * Check payment status for the authenticated student or a specific student.
     */
    public function checkStatus(Request $request)
    {
        $user = Auth::user();
        $studentId = $request->query('student_id');

        // If no student_id provided, assume current user is a student
        if (!$studentId && $user && method_exists($user, 'isStudent') && $user->isStudent()) {
             // Assuming User model has relation or method to get Student record if strictly separate
             // Or if Auth user IS the student
             $studentId = $user->id;
        }

        if (!$studentId) {
             return response()->json(['error' => 'Student ID required'], 400);
        }

        // Find current active term for the school
        // This logic depends on how "current term" is defined.
        // We might need to pass term_id or infer it.
        $termId = $request->query('term_id');

        if (!$termId) {
             // Try to find active term
             $student = Student::find($studentId);
             if (!$student) return response()->json(['error' => 'Student not found'], 404);

             $term = Term::where('school_id', $student->school_id)
                 ->where('status', 'active')
                 ->first();

             if (!$term) {
                 return response()->json(['message' => 'No active term found'], 404);
             }
             $termId = $term->id;
        }

        $payment = Payment::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->first();

        if ($payment && $payment->status === 'paid') {
            return response()->json(['status' => 'paid', 'payment' => $payment]);
        }

        // Check required fee
        $school = School::find(Student::find($studentId)->school_id);
        $fee = $school->term_fee ?? 0;

        return response()->json([
            'status' => 'pending',
            'required_amount' => $fee,
            'message' => 'Payment required to access portal.'
        ]);
    }
}
