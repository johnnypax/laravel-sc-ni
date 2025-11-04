<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $r){
        $r->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8'
        ]);

        $user = User::create([
            'name'=>$r->name,
            'email'=>$r->email,
            'password'=>Hash::make($r->password)
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['token'=>$token], 201);
    }

    public function login(Request $r){
        $r->validate(['email'=>'required|email','password'=>'required']);
        $user = User::where('email',$r->email)->first();

        if(!$user || !Hash::check($r->password,$user->password)) {
            throw ValidationException::withMessages(['email' => 'Credenziali errate']);
        }

        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json(['token'=>$token]);
    }

    public function logout(Request $r){
        $r->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logout eseguito']);
    }
}
