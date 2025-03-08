<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google’s OAuth page.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function callbackGoogle(){
        try {
            // dd(Socialite::driver('google')->user());

            $google_user = Socialite::driver('google')->stateless()->user();

            $user = User::where('google_id', $google_user->getId())->first();
            // dd($google_user);

            if (!$user) {
                $new_user = User::create([
                    'name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' => $google_user->getId(),
                    // 'role' => ''
                ]);
                // dd($new_user);

                Auth::login($new_user);
                return redirect()->intended('dashboard');
            }else{
                // dd('else');
                Auth::login($user);
                return redirect()->intended('dashboard');
            }
        } catch (\Throwable $th) {
            dd('something went wrong! '. $th->getMessage());
        }
    }
}