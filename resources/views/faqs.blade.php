<x-guest-layout>
    <div x-data="{ openTab: null }" class="pt-4 bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
            <div>
                <x-authentication-card-logo />
            </div>

            <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg prose dark:prose-invert">
                <h2 class="text-center text-4xl">FAQs</h2>

                <!-- Accordion item 1 -->
                <div class="mt-2">
                    <button @click="openTab = openTab === 1 ? null : 1" class="text-left w-full h-8 text-xl font-bold">
                        Why does my domain say it is "Pending DNS Verification"?
                    </button>
                    <div x-cloak x-show="openTab === 1" class="p-4 mt-2 bg-gray-700">
                        <span>Bit Conquest leverages DNS records in order to confirm that you own the domain you have added. To get your domain into a verified status, you will need to add a TXT record to your domain's DNS records. Your hosting provider should provide you with instructions to accomplish this. Once you have added the record to your domain, it may take several hours before it registers with our system because of DNS propagation TTLs, so give it some time.</span>
                    </div>
                </div>

                <!-- Accordion item 2 -->
                <div class="mt-2">
                    <button @click="openTab = openTab === 2 ? null : 2" class="text-left w-full h-8 mt-4 text-xl font-bold">
                        Why do you need to verify my domains?
                    </button>
                    <div x-cloak x-show="openTab === 2" class="p-4 mt-2 bg-gray-700">
                        <span>Bit Conquest offers many helpful services to keep you up-to-date about the status of your domains. In order to provide you with these services, Bit Conquest will periodically make requests to the website hosted at that domain in order to confirm details about it for you. Because other websites may not be expecting our requests or able to serve them correctly, we opt to not make requests to unverified sites.</span>
                    </div>
                </div>

                <!-- Accordion item 3 -->
                <div class="mt-2">
                    <button @click="openTab = openTab === 3 ? null : 3" class="text-left w-full h-8 mt-4 text-xl font-bold">
                        Why are we only able to log in via GitHub?
                    </button>
                    <div x-cloak x-show="openTab === 3" class="p-4 mt-2 bg-gray-700">
                        <span>Not every company is a security company. We would rather spend our time implementing great new features that make your life better and more fun instead of worrying about the latest security vulnerabilities. GitHub has a proven track record for security and provides us with a free way to empower people to get started easily.</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>

