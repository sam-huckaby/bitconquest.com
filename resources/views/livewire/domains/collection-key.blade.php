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

<div class="w-full flex flex-col justify-center items-center p-8 bg-gray-200 dark:bg-gray-500 rounded-t">
    <div class="w-full flex flex-row justify-start items-center p-2 text-xl">
        To Verify ownership domain ownership (and display on your showcase), add a TXT record to your domain's DNS settings:
    </div>
    <div class="w-full flex flex-row justify-between items-center p-2">
        <div class="flex flex-col justify-center items-center">
            <span class="border-b border-b-solid border-b-black text-xl font-bold">DNS Record Type</span>
            <span class="text-xl">TXT</span>
        </div>
        <div class="flex flex-col justify-center items-center">
            <span class="border-b border-b-solid border-b-black text-xl font-bold">Host</span>
            <span class="text-xl">@</span>
        </div>
        <div class="flex flex-col justify-center items-center">
            <span class="border-b border-b-solid border-b-black text-xl font-bold">Value</span>
            <div class="flex flex-row justify-center items-center">
                <span id="collectionKey" class="text-xl">{{ $this->collection_key }}</span>
                <button onclick="copyCollectionKey()" class="ml-2"><img src="{{ asset('icons/Copy.svg') }}" alt="Copy collection key" /></button>
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
