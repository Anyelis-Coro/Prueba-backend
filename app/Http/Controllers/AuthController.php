<?php

namespace App\Http\Controllers;

    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Validation\ValidationException;
    
    class AuthController 
    {
        public function login(Request $request)
        {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            $user = User::where('email',
            $request->email)->first();
    
            if (! $user || ! Hash::check($request->password,
            $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Las credenciales
                    proporcionadas son incorrectas.'],
                ]);
            }
    
            $token =
            $user->createToken('api-token')->plainTextToken;
    
            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
        }
    }
