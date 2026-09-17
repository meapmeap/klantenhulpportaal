<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile(Request $request) {
        return new UserResource($request->user());
    }

    public function index() { 
        $users = User::all(); 
        
        return UserResource::collection($users); 
    }

    public function store(StoreUserRequest $request) { 
        $user = User::create($request->validated()); 
        
        return new UserResource($user); 
    }

    public function register(RegisterUserRequest $request) { 
        $data = $request->validated(); 
        $data['rol'] = 'user'; 
        
        $user = User::create($data); 
        
        return new UserResource($user); 
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email_adres' => ['required', 'email'],
            'wachtwoord' => ['required'],
        ]);

        if (!Auth::attempt([
            'email_adres' => $credentials['email_adres'],
            'password' => $credentials['wachtwoord'],
        ])) {
            return response()->json([
                'message' => 'De ingevoerde gegevens zijn onjuist.'
            ], 401);
        }

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Succesvol ingelogd.'
        ]);
    }
}
