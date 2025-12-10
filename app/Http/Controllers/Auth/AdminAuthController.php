<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
      $request->validate([
          'email' => 'required|email',
          'password' => 'required|string',
      ]);

        $admin = \App\Models\Admin::where('email', $request->email)->first();
        if (!$admin || !\Illuminate\Support\Facades\Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $admin->createToken('auth_token')->plainTextToken;
        return response()->json(['message' => 'Admin logged in successfully', 'admin' => $admin, 'access_token' => $token, 'token_type' => 'Bearer'], 200);
    }
}
