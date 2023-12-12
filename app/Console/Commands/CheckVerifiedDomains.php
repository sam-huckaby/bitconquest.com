<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Services\DnsRecordService;
use App\Services\DomainScoreService;
use Illuminate\Console\Command;

class CheckVerifiedDomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-verified-domains';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a verification check on each domain that is verified to check for any deleted records';

    /**
     * Execute the console command.
     */
    public function handle(DnsRecordService $dnsRecordService, DomainScoreService $domainScoreService)
    {
        $domains = Domain::with('team')->where('verified', true)->get();

        foreach($domains as $domain) {
            $dnsRecordService->verifyDomain($domain, $domain->team->collection_key);
            $domainScoreService->score($domain->id);
        }
    }
}
