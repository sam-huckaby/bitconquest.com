<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Services\DnsRecordService;
use App\Services\DomainScoreService;
use Illuminate\Console\Command;

class CheckUnverifiedDomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-unverified-domains';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attempt to verify unverified domains';

    /**
     * Execute the console command.
     * This command is separate from the CheckVerifiedDomains command, so that they can be run on different intervals
     */
    public function handle(DnsRecordService $dnsRecordService, DomainScoreService $domainScoreService)
    {
        $domains = Domain::with('team')->where('verified', false)->get();

        foreach($domains as $domain) {
            $dnsRecordService->verifyDomain($domain, $domain->team->collection_key);
            $domainScoreService->score($domain->id);
        }
    }
}
