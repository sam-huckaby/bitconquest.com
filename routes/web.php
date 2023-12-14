<?php

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use App\Http\Controllers\DomainController;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('/auth/callback', function () {
    $githubUser = Socialite::driver('github')->user();

    // TODO: A Service Class would be a better place for this logic maybe?
    // I built the below service, but it doesn't work yet.
    //$AuthService = app(AuthService::class);
    //$user = $AuthService->loginGitHub($githubUser);

    $user = User::where('email', $githubUser->email)->first();

    // If the user that GitHub returned exists in our system, then go ahead and log them in
    if (!$user) {
        Log::info('GitHub User', ['$githubUser' => $githubUser]);
        // If the user does not exist in our system, create it via Fortify, so the personal team creation is handled
        try {
            $userCreator = new CreateNewUser();

            $user = $userCreator->create([
                'username' => $githubUser->nickname,
                'name' => $githubUser->name ?? $githubUser->nickname,
                'email' => $githubUser->email,
                'password' => '', // Passwords are for the weak
                'github_id' => $githubUser->id,
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);
        } catch (Exception $err) {
            Log::info('User Creation Failed', ['$err' => $err]);
        }
    } elseif ( !$user->github_id ) {
        // This is the first time logging in with GitHub (This is probably not needed, since we only support GitHub)
        $user->github_id = $githubUser->id;
        $user->save();
    }

    Auth::login($user);

    return redirect('/dashboard');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/faqs', function () {
    return view('faqs');
});

Route::get('/pricing', function () {
    return view('pricing');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/collection', [DomainController::class, 'index'])->name('collection');
});

// This needs to be last, so that any higher-priority names get routed to first
Route::get('/{username}', [DomainController::class, 'showcase'])->name('showcase');
