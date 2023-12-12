<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $collection_key = '';

    public function mount()
    {
        $this->collection_key = "bitconquest-verifier_" . Auth::user()->currentTeam->collection_key;
    }
}; ?>

<div class="w-full flex flex-col justify-center items-center p-4 bg-gray-200 dark:bg-gray-800 dark:text-neutral-200 rounded-t" x-data="{ expanded: false }">
    <div class="w-full flex flex-row justify-center items-center p-0" x-on:click="expanded = ! expanded">
        <span x-cloak x-show="expanded" class="cursor-pointer select-none">Hide</span>
        <span x-show="! expanded" class="cursor-pointer select-none">Learn more about verification</span>
    </div>
    <div x-cloak x-show="expanded" class="w-full flex flex-row justify-start items-center p-2 text-xl">
        To Verify ownership domain ownership (and display on your showcase), add a TXT record to your domain's DNS settings:
    </div>
    <div x-cloak x-show="expanded" class="w-full grid grid-cols-[200px_200px_1fr] p-2">
            <span class="p-2 border-b border-b-solid border-b-black text-xl font-bold">DNS Record Type</span>
            <span class="p-2 border-b border-b-solid border-b-black text-xl font-bold">Host</span>
            <span class="p-2 border-b border-b-solid border-b-black text-xl font-bold">Value</span>
            <span class="p-2 text-xl dark:bg-gray-700 w-full">TXT</span>
            <span class="p-2 text-xl dark:bg-gray-700">@</span>
            <div class="p-2 flex flex-row justify-start items-center dark:bg-gray-700" x-data="{ copied: false }">
                <span id="collectionKey" class="text-xl">{{ $this->collection_key }}</span>
                <button @click="copied = true; setTimeout(() => copied = false, 3000)" onclick="copyCollectionKey()" class="ml-2 dark:text-neutral-200">
                    <span x-show="!copied"><x-icon-copy class="h-[20px]" /></span>
                    <span x-show="copied"><x-icon-checkmark class="h-[20px]" /></span>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    function copyCollectionKey() {
        var copyText = document.getElementById("collectionKey").innerText;
        navigator.clipboard.writeText(copyText);
    }
</script>
