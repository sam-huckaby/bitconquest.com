<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Services\DnsRecordService;
use Illuminate\Console\Command;

class GatherDNSRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:gather-d-n-s-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve all the DNS records that we track for all verified domains in the system';

    /**
     * Execute the console command.
     */
    public function handle(DnsRecordService $dnsRecordService)
    {
        $domains = Domain::where('verified', true)->with('latestDnsHistory')->get();

        foreach ($domains as $domain) {
            $records = $dnsRecordService->gatherAllRecords($domain);
            $recordsJson = json_encode($records);

            // Compare the latest DNS history with the new record
            if (!$domain->latestDnsHistory || $domain->latestDnsHistory->records !== $recordsJson) {
                // Only insert if there's no latest record or it's different from the new record
                $domain->dnsHistories()->create([
                    'records' => $recordsJson
                ]);
            }
        }
    }
}
