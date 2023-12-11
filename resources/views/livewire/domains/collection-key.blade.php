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

<div class="w-full flex flex-col justify-center items-center p-8 bg-gray-200 dark:bg-gray-800 dark:text-neutral-200 rounded-t">
    <div class="w-full flex flex-row justify-start items-center p-2 text-xl">
        To Verify ownership domain ownership (and display on your showcase), add a TXT record to your domain's DNS settings:
    </div>
    <div class="w-full grid grid-cols-[200px_200px_1fr] p-2">
            <span class="p-2 border-b border-b-solid border-b-black text-xl font-bold">DNS Record Type</span>
            <span class="p-2 border-b border-b-solid border-b-black text-xl font-bold">Host</span>
            <span class="p-2 border-b border-b-solid border-b-black text-xl font-bold">Value</span>
            <span class="p-2 text-xl dark:bg-gray-700 w-full">TXT</span>
            <span class="p-2 text-xl dark:bg-gray-700">@</span>
            <div class="p-2 flex flex-row justify-start items-center dark:bg-gray-700">
                <span id="collectionKey" class="text-xl">{{ $this->collection_key }}</span>
                <button onclick="copyCollectionKey()" style="color: white;" class="ml-2 dark:text-neutral-200"><img src="{{ asset('icons/Copy.svg') }}" alt="Copy collection key" /></button>
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
