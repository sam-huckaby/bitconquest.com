<?php

namespace App\Services;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthService
{
    public function loginGitHub($githubUser)
    {
        // TODO: A Service Class would be a better place for this logic maybe?
        $user = User::where('username', $githubUser->nickname)->first();

        // If the user that GitHub returned exists in our system, then go ahead and log them in
        if (!$user) {
            // If the user does not exist in our system, create it via Fortify, so the personal team creation is handled
            try {
                $userCreator = new CreateNewUser();

                $user = $userCreator->create([
                    'username' => $githubUser->nickname,
                    'name' => $githubUser->name,
                    'email' => $githubUser->email,
                    'password' => '', // Passwords are for the weak
                    'github_id' => $githubUser->id,
                    'github_token' => $githubUser->token,
                    'github_refresh_token' => $githubUser->refreshToken,
                ]);
            } catch (Exception $err) {
                Log::info('User Creation Failed', ['$err' => $err]);
            }
        } elseif (!$user->github_id) {
            // This is the first time logging in with GitHub (This is probably not needed, since we only support GitHub)
            $user->github_id = $githubUser->id;
            $user->save();
        }

        return $user;
    }
}
