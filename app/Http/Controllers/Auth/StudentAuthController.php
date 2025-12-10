<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'bloodType' => 'required|string|max:3',
            'sex' => 'required|string|max:10',
            'birthday' => 'required|date',
            'guardianName' => 'required|string|max:255',
            'classId' => 'required|integer|exists:class_rooms,id',
            'gradeId' => 'required|integer|exists:grades,id',
            'school_id' => 'required|integer|exists:schools,id',
            'username' => 'required|string|max:50|unique:students',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'passport' => 'nullable|string|max:100',
            'password' => "required|string|min:8|confirmed"
        ]);

        $student = \App\Models\Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'surname' => $request->surname,
            'bloodType' => $request->bloodType,
            'sex' => $request->sex,
            'birthday' => $request->birthday,
            'guardianName' => $request->guardianName,
            'classId' => $request->classId,
            'gradeId' => $request->gradeId,
            'school_id' => $request->school_id,
            'username' => $request->username,
            'phone' => $request->phone,
            'address' => $request->address,
            'passport' => $request->passport,  
            'tenant_id' => tenant('id'), 
            'password' => Hash::make($request->password),
        ]);

        $token = $student->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'Student registered successfully', 'student' => $student,'token'=>$token], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8|string'
        ]);

        $student = \App\Models\Student::where('email', $request->email)->first();
        if (!$student || !\Illuminate\Support\Facades\Hash::check($request->password, $student->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $student->createToken('auth_token')->plainTextToken;
        return response()->json(['message' => 'student logged in successfully', 'student' => $student, 'access_token' => $token, 'token_type' => 'Bearer'], 200);

    }

}
