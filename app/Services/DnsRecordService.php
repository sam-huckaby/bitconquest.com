<?php

namespace App\Services;

class DnsRecordService
{
    // Prefix all TXT records with this value
    private $PREFIX = "bitconquest-verifier_";

    public function checkDnsTxtRecord($url, $teamCollectionKey)
    {
        $records = dns_get_record($url, DNS_TXT);
        foreach ($records as $record) {
            if (isset($record['txt']) && strpos($record['txt'], $this->PREFIX.$teamCollectionKey) !== false) {
                return true;
            }
        }
        return false;
    }

    public function verifyDomain($domain, $teamCollectionKey)
    {
        $domain->verified = $this->checkDnsTxtRecord($domain->hostname.'.'.$domain->tld, $teamCollectionKey);
        $domain->save();
    }
}
