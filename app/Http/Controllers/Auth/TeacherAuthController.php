<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:teachers,username',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'passport' => 'nullable|string|max:255',
            'bloodType' => 'nullable|string|max:3',
            'sex' => 'required|in:male,female,other',
            'birthday' => 'required|date',
            'school_id' => 'required|integer|exists:schools,id',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $teacher = Teacher::create([
            'username' => $request->username,
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'passport' => $request->passport,
            'bloodType' => $request->bloodType,
            'sex' => $request->sex,
            'birthday' => $request->birthday,
            'school_id' => $request->school_id,
            'tenant_id' => 'elite-school',
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'message' => 'Teacher registered successfully',
            'data' => $teacher
        ], 201);
    } 
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);
        $teacher = Teacher::where('username', $request->username)->first();
        if (!$teacher || !Hash::check($request->password, $teacher->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $token = $teacher->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
        ]);
    }
}
