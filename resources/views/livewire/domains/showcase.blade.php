<?php

use App\Models\Domain;
use App\Models\User;
use App\Services\DomainColorService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Volt\Component;

new class extends Component
{
    protected $domainColorService;

    public string $username;
    public User|null $user;
    public Collection $domains;

    public function mount(DomainColorService $domainColorService): void
    {
        $this->domainColorService = $domainColorService;
        $username = request()->username;

        // Retrieve the user by username
        $this->user = User::where('name', $username)->first();

        if ($this->user) {
            // Retrieve the personal team of the user
            $personalTeamId = $this->user->personalTeam()['id'];

            if ($personalTeamId) {
                // Retrieve all domains that belong to the personal team
                $this->domains = Domain::where([
                    ['team_id', $personalTeamId],
                    ['verified', true],
                ])->latest()->get();
                // $domains now contains all domains associated with the user's personal team
            } else {
                // Handle case where the personal team is not found
            }
        } else {
            // Funny idea: track all the of usernames people try, and hit them up on X (fka Twitter) to let them know
            Log::info('User Not Found', ['Username requested: ', $username]);
        }
    }

    public function getBG($tld): string
    {
        $this->domainColorService = app(DomainColorService::class);
        $background = $this->domainColorService->tldTailwindBg($tld);

        return $background;
    }
}; ?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Bit Conquest - Showcase Your Domains</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Feeling feisty -->
    @vite(['resources/css/showcase.css', 'resources/js/showcase.js'])

</head>

<body class="antialiased">
    <div id="main_container" class="relative sm:flex sm:flex-col sm:justify-start sm:items-center min-h-screen bg-center bg-gray-100 dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <div id="hero_banner_nav" class="w-full h-[72px] p-6 flex flex-row justify-start items-center bg-gradient-to-br from-slate-600 via-slate-800 to-slate-700">
            <img id="hero_logo" src="{{ asset('img/bitconquest-logo.png') }}" alt="Bit Conquest Logo" height="48" width="48" />
            <h2 class="text-4xl text-white">Bit Conquest</h2>
        </div>

        <!-- Top-right nav for the home page -->
        @if (Route::has('login'))
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
            @auth
            <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-400 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Go to Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="font-semibold text-gray-400 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-400 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
            @endif
            @endauth
        </div>
        @endif
        @unless ($user)
        <div class="w-full text-center mt-8 text-black dark:text-white">WOAH! THAT USER DOESN'T EXIST!</div>
        @endunless
        @if ($user)
        <div class="w-full flex flex-col items-center">
            <h1 class="mt-4 text-2xl font-bold sm:text-3xl md:text-4xl py-4 text-neutral-800 dark:text-neutral-200">{{ $user->name }}'s domains</h1>
            <section class="flex flex-col py-4">
                <div class="w-full container flex flex-row flex-wrap gap-4 px-4 justify-center md:gap-8 md:px-6 lg:gap-10">
                    @foreach ($domains as $domain)
                    <div wire:key="{{ $domain->id }}" class="relative bg-gray-300 dark:bg-gray-700 border border-solid border-black dark:border-transparent w-[400px] rounded-lg shadow-lg p-4 flex flex-col items-center">
                        <span class="text-2xl font-bold tracking-tighter sm:text-3xl md:text-4xl flex flex-row items-center justify-center w-full">
                            @if ($domain->verified)
                            <img src="{{ asset('img/bitconquest-logo.png') }}" alt="Bit Conquest Verified Domain" class="mr-2" height="24" width="24" />
                            @endif
                            <span class="truncate text-neutral-800 dark:text-neutral-200" title="{{$domain->hostname}}.{{$domain->tld}}">{{ $domain->hostname }}</span>
                            <span class="{{ $this->getBG($domain->tld) }} text-lg rounded-full ml-2 py-1 px-3 tracking-wide">.{{ $domain->tld }}</span>
                        </span>
                        <div class="{{ $this->getBG($domain->tld) }} h-1 w-16 mt-4 mb-4"></div>
                        <img width="300" height="150" src="data:image/png;base64,{{ $domain->flair }}" alt="Flair for {{ $domain->hostname }}.{{ $domain->tld }}" />
                        <div class="{{ $this->getBG($domain->tld) }} h-1 w-16 mt-4 mb-4"></div>
                        <p class="mt-4 font-bold text-zinc-800 dark:text-zinc-300 md:text-lg lg:text-base xl:text-lg">Score: {{ $domain->score }}</p>
                    </div>
                    @endforeach
                </div>
            </section>
        </div>
        @endif
    </div>
</body>

</html>
