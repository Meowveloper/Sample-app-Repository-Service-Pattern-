<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthApiController extends Controller
{
    public function signInWithFacebook(Request $request)
    {
        $access_token = $request->input('access_token');
        //TODO::need to move to auth service
        $socialUser = Socialite::driver('facebook')->statless()->userFromToken($access_token);

        // Check if the user with the given Facebook ID exists
        $user = User::where('facebook_id', $socialUser->id)->first();

        // If the user doesn't exist, create a new user
        if (!$user) {
            $user = User::create([
                'name' => $socialUser->name,
                'email' => $socialUser->email,
                'facebook_id' => $socialUser->id,
                // Additional fields if needed
            ]);
        }

        // Issue token or perform login logic
        $token = $user->createToken('user')->accessToken;
        $user = $user->update(['remember_token'=>$token]);

        return response()->json(['data' => $user,'message' => 'Successfully Register', 'status' => 'success'], 200);
    }

    public function signInWithGoogle(Request $request)
    {
        $access_token = $request->input('access_token');
        //TODO::need to move to auth service
        $socialUser = Socialite::driver('google')->statless()->userFromToken($access_token);

        // Check if the user with the given Google ID exists
        $user = User::where('google_id', $socialUser->id)->first();

        // If the user doesn't exist, create a new user
        if (!$user) {
            $user = User::create([
                'name' => $socialUser->name,
                'email' => $socialUser->email,
                'google_id' => $socialUser->id,
                // Additional fields if needed
            ]);
        }

        // Issue token or perform login logic
        $token = $user->createToken('user')->accessToken;
        $user = $user->update(['remember_token'=>$token]);

        return response()->json(['data' => $user,'message' => 'Successfully Sign in with google', 'status' => 'success'], 200);
    }

    public function signInWithApple(Request $request)
    {
        $access_token = $request->input('access_token');
        //TODO::need to move to auth service
        $socialUser = Socialite::driver('apple')->statless()->userFromToken($access_token);

        // Check if the user with the given Apple ID exists
        $user = User::where('apple_id', $socialUser->id)->first();

        // If the user doesn't exist, create a new user
        if (!$user) {
            $user = User::create([
                'name' => $socialUser->name,
                'email' => $socialUser->email,
                'apple_id' => $socialUser->id,
            ]);
        }

        // Issue token or perform login logic
        $token = $user->createToken('user')->accessToken;
        $user = $user->update(['remember_token'=>$token]);

        return response()->json(['data' => $user,'message' => 'Successfully Sign in with google', 'status' => 'success'], 200);
    }



}
