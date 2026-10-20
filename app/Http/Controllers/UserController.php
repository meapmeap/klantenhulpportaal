<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AccountCreatedNotification;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function profile(Request $request) {
        return new UserResource($request->user());
    }

    public function index(Request $request) {
        if ($request->user()->rol !== 'admin') { abort(403); }

        return UserResource::collection(
            User::all()
        );
    }

    public function admins() {
        $admins = User::where('rol', 'admin')->get();
        
        return UserResource::collection($admins);
    }

    public function store(StoreUserRequest $request) { 
        $user = User::create($request->validated()); 
        
        return new UserResource($user); 
    }

    public function register(RegisterUserRequest $request) { 
        $data = $request->validated(); 
        $data['rol'] = 'user'; 
        
        $user = User::create($data); 

        $user->notify(new AccountCreatedNotification());
        
        return new UserResource($user); 
    }

    public function update(StoreUserRequest $request, User $user){
        $data = $request->validated();

        if (empty($data['wachtwoord'])) {
            unset($data['wachtwoord']);
        }

        $user->update($data);

        return new UserResource($user);
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email_adres' => ['required', 'email'],
            'wachtwoord' => ['required'],
        ]);

        if (!Auth::attempt([
            'email_adres' => $credentials['email_adres'],
            'password' => $credentials['wachtwoord'],
        ])) {
            return response()->json(['message' => 'De ingevoerde gegevens zijn onjuist.'], 401);
        }

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Succesvol ingelogd.',
            'user' => Auth::user(),
            'session_id' => $request->session()->getId(),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Succesvol uitgelogd.'
        ]);
    }

    public function destroy(User $user)
    {
        return DB::transaction(function () use ($user) {
            $hasOpenCreatedTickets = $user->tickets()
                ->where('status', '!=', 'Afgehandeld')
                ->exists();

            $hasOpenAssignedTickets = $user->assignedTickets()
                ->where('status', '!=', 'Afgehandeld')
                ->exists();

            if ($hasOpenCreatedTickets || $hasOpenAssignedTickets) {
                return response()->json(['message' => 'Deze gebruiker kan niet worden verwijderd, omdat er nog tickets zijn die niet zijn afgehandeld.'], 422);
            }

            $user->delete();

            return response()->json(['message' => 'Gebruiker succesvol verwijderd.']);
        });
    }
}
