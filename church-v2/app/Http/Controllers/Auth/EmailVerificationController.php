<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class EmailVerificationController extends Controller
{
    public function sendVerificationEmail(User $user)
    {
        // Generate a unique verification URL
        $verificationUrl = URL::temporarySignedRoute(
            'verify.email', now()->addMinutes(60), ['user' => $user->id]
        );

        // Send the verification email
        Mail::to($user->email)->send(new VerificationEmail($user, $verificationUrl));
    }

    public function verifyEmail(Request $request, User $user)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(['message' => 'Invalid or expired link.'], 400);
        }

        // Mark the user as verified
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('login')->with('status', 'Your email has been verified!');
    }
}
