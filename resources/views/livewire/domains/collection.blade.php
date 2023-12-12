<?php

use App\Models\Domain;
use App\Services\DnsRecordService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component
{
    public Collection $domains;

    protected $dnsRecordService;

    public function mount(): void
    {
        $this->getDomains();
    }

    public function getDomains(): void
    {
        $teamId = Auth::user()->currentTeam->id;

        $this->domains = Domain::where('team_id', $teamId)
            ->orderBy('hostname', 'asc')
            ->get();

        // Instantiate the service, because mount() isn't a true constructor
        // Discovered here: https://laracasts.com/discuss/channels/livewire/calling-function-in-services-class-is-not-working
        $this->dnsRecordService = app(DnsRecordService::class);
        $teamCollectionKey = auth()->user()->currentTeam->collection_key;

        foreach ($this->domains as $domain) {
            if (!$domain->verified) {
                $this->dnsRecordService->verifyDomain($domain, $teamCollectionKey);
            }
        }
    }

    #[On('domain-collected')]
    public function handleCollection(): void
    {
        $this->getDomains();
    }

    public function deleteDomain(Domain $domain): void
    {
        $this->authorize('delete', $domain);

        $domain->delete();

        $this->getDomains();
    }
};

?>

<div class="mt-6 bg-gray-100/50 dark:bg-gray-900/50 text-neutral-800 dark:text-neutral-200 shadow-sm divide-y rounded-b">
    <div class="w-full grid grid-cols-[75px_1fr_150px_100px_75px] p-4">
        <div class="font-bold text-center">{{ __('Verified') }}</div>
        <div class="font-bold">{{ __('Domain') }}</div>
        <div class="font-bold">{{ __('Score') }}</div>
        <div class="font-bold">{{ __('Flair') }}</div>
        <div class="font-bold"></div>
    </div>
    <div class="w-full flex flex-col pb-4 rounded-b">
        @foreach ($domains as $domain)
        <div class="flex flex-col" wire:key="{{ $domain->id }}">
            <div class="w-full grid grid-cols-[75px_1fr_150px_100px_75px] px-4">
                <div class="{{ $loop->even ? 'bg-gray-500/10 dark:bg-white/10' : '' }} flex flex-row justify-center items-center py-2">
                    @if ($domain->verified)
                    <img src="{{ asset('img/bitconquest-logo.png') }}" alt="Verified Mark" height="40" width="40" />
                    @endif
                </div>
                <div class="{{ $loop->even ? 'bg-gray-500/10 dark:bg-white/10' : '' }} flex flex-row justify-start items-center py-2 text-2xl">{{ $domain->hostname }}.{{ $domain->tld }}</div>
                <div class="{{ $loop->even ? 'bg-gray-500/10 dark:bg-white/10' : '' }} flex flex-row justify-start items-center py-2 text-2xl">{{ $domain->score }}</div>
                <div class="{{ $loop->even ? 'bg-gray-500/10 dark:bg-white/10' : '' }} py-2"><img class='h-[50px]' height={50} src={{'data:image/png;base64,' . $domain->flair }} alt="Flair for {{ $domain->hostname }}.{{ $domain->tld }}" /></div>
                <div class="{{ $loop->even ? 'bg-gray-500/10 dark:bg-white/10' : '' }} py-2 flex flex-row justify-center items-center">
                    <x-dropdown alignment="right">
                        <x-slot name="trigger">
                            <button class="h-[50px] w-[50px] flex flex-row justify-center items-center dark:text-neutral-200 bg-white/20 hover:bg-white/10">
                                <x-icon-three-dots class="h-[25px]" />
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <button wire:click="deleteDomain({{ $domain->id }})" wire:confirm="Are you sure to delete this domain?" class="w-full p-4 hover:bg-red-600">
                                {{ __('Delete') }}
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
