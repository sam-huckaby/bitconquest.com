<?php

use App\Models\Domain;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    // The domains that belong to the current team
    public Collection $domains;
    public $totalDomains = 0;

    public function mount()
    {
        $teamId = Auth::user()->currentTeam->id;

        $this->domains = Domain::where('team_id', $teamId)
            ->selectRaw('verified, COUNT(*) as count')
            ->groupBy('verified')
            ->get();

        $this->totalDomains = 0;

        foreach ($this->domains as $kind) {
            $this->totalDomains += $kind->count;
        }
    }
}; ?>

<div class="w-full dark:text-neutral-200">
    <div class="text-2xl mb-4">{{ __('Domain Verification Stats') }}</div>
    <div class="w-full flex flex-row justify-center items-center">
        @foreach ($domains as $kind)
        <div class="{{ $kind->verified ? 'bg-green-400 dark:bg-green-800' : 'bg-blue-400 dark:bg-blue-800' }} h-16 text-neutral-800 dark:text-neutral-200 flex flex-row justify-center items-center" style="width: calc(100% * {{ $kind->count }} / {{$totalDomains}});">
            @unless ($kind->verified)
            <span class="font-bold">{{ __('Unverified') }}</span>
            @endunless
            @if ($kind->verified)
            <span class="font-bold">{{ __('Verified') }}</span>
            @endif
        </div>
        @endforeach
    </div>
</div>
