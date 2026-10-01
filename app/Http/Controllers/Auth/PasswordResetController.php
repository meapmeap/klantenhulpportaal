<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email_adres' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink([
            'email_adres' => $request->email_adres,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Als het e-mailadres bij ons bekend is, ontvang je een e-mail met verdere instructies.',
            ]);
        }

        if ($status === Password::RESET_THROTTLED) {
            return response()->json([
                'message' => 'Er is recent al een reset aangevraagd. Probeer het later opnieuw.',
            ], 429);
        }

        return response()->json([
            'message' => 'De resetmail kon niet worden verstuurd. Probeer het later opnieuw.',
        ], 422);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_adres' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Controleer de ingevulde gegevens.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $status = Password::reset(
            [
                'email_adres' => $request->email_adres,
                'token' => $request->token,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
            ],
            function ($user, $password) {
                $user->wachtwoord = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Je wachtwoord is succesvol gewijzigd.',
            ]);
        }

        return response()->json([
            'message' => 'De resetlink is ongeldig of verlopen. Vraag een nieuwe resetlink aan.',
        ], 422);
    }
}
