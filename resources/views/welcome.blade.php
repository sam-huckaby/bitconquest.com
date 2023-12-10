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
    @vite(['resources/css/welcome.css', 'resources/js/welcome.js'])

</head>

<body class="antialiased">
    <div id="main_container" class="relative sm:flex sm:flex-col sm:justify-start sm:items-center min-h-screen bg-center bg-gray-100 dark:bg-gray-900 selection:bg-red-500 selection:text-white">
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

        <div id="hero_banner_nav" class="transition-all duration-200 w-full p-6 flex flex-col items-center justify-center bg-gradient-to-br from-slate-600 via-slate-800 to-slate-700">
            <img id="hero_logo" src="{{ asset('img/bitconquest-logo.png') }}" alt="Bit Conquest Logo" height="150" width="150" />
            <h2 class="text-4xl text-white">Bit Conquest</h2>
        </div>

        <div class="relative h-64 w-full max-w-7xl">
            <div class="hero-container flex flex-row justify-between w-full overflow-hidden h-[318px]">
                <img width="350" height="318" class="hidden sm:block absolute top-24 left-12 -rotate-12 z-[1] drop-shadow-[8px_4px_8px_rgba(0,0,0,0.3)]" alt="Conquest Card for whnvr.com" src="img/whnvr-domaincard.png" />
                <img width="350" height="318" class="absolute top-8 left-[calc(50%-175px)] z-[2] drop-shadow-[8px_2px_8px_rgba(0,0,0,0.3)]" alt="Conquest Card for bitconquest.com" src="img/bitconquest-domaincard.png" />
                <img width="350" height="318" class="hidden sm:block absolute top-24 right-12 rotate-12 z-[1] drop-shadow-[8px_0px_8px_rgba(0,0,0,0.3)]" alt="Conquest Card for samhuckaby.com" src="img/samhuckaby-domaincard.png" />
            </div>
        </div>

        <div class="max-w-7xl mx-auto p-6 lg:p-8 dark:text-neutral-200 z-[3] bg-gray-100 dark:bg-gray-900 border-t border-t-solid border-t-gray-500 drop-shadow-[-10px_-10px_8px_rgba(0,0,0,0.3)]">
            <div class="relative">
                <h1 class="text-4xl font-bold py-2">Discover Your Domain Dynasty!</h1>
                <p class='relative'>Are you a domain collector, hoarding URLs like they&apos;re going out of style? Do you have a graveyard of unused domains gathering virtual dust? Or maybe, you&apos;re the savvy domain investor, waiting for the right moment to unleash your web real estate onto the world? Whatever your style, it&apos;s time to step into the spotlight with Bit Conquest, the app where domains get their day!</p>

                <h2 class='text-2xl font-bold py-2'>Flaunt Your Digital Empire</h2>
                <p>Bit Conquest is not just another domain portfolio tool. It&apos;s a virtual kingdom where each of your domains, from the quirky to the quintessential, shines in its own right. This platform lets you showcase your entire collection, even those oddball impulse buys you made at 3 AM (we know, it seemed like a good idea at the time).</p>

                <h2 class='text-2xl font-bold py-2'>Collect Flair for Every Domain</h2>
                <p>Every domain is unique, and at Bit Conquest, each gets its moment of glory. You&apos;ll earn custom flair for each domain, turning your collection into a vibrant tapestry of digital achievement. It&apos;s like scout badges, but for your web domains. How cool is that?</p>

                <h2 class='text-2xl font-bold py-2'>Share with Friends, Impress Strangers</h2>
                <p>With Bit Conquest, sharing your domain collection becomes a social affair. Show off your digital prowess to friends, family, and yes, even those envious competitors. Let them marvel at your domain diversity and your uncanny knack for snagging cool URLs.</p>
            </div>
            <div class="h-36 flex flex-col justify-center items-center">
                <h2 class="text-4xl">Leaderboards coming soon!</h2>
            </div>
        </div>
    </div>
</body>

</html>
