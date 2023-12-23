<?php

namespace App\Services;

class DnsRecordService
{
    // Prefix all TXT records with this value
    private $PREFIX = "bitconquest-verifier_";

    private function normalize($dnsRecords)
    {
        // Function to remove 'ttl' and sort each individual record by keys
        $processRecord = function (&$record) {
            unset($record['ttl']); // Remove the 'ttl' field
            ksort($record);
        };

        // Process each record: remove 'ttl' and then sort
        array_walk($dnsRecords, $processRecord);

        // Function to compare records for sorting the entire array
        $recordComparator = function ($a, $b) {
            if ($a['type'] === $b['type']) {
                // Additional sorting logic for various record types
                // Check and compare fields based on record type
                $sortOrder = ['pri', 'weight', 'port', 'txt', 'ip', 'target', 'order', 'preference', 'replacement'];
                foreach ($sortOrder as $field) {
                    if (isset($a[$field]) && isset($b[$field])) {
                        $comparison = strcmp((string)$a[$field], (string)$b[$field]);
                        if ($comparison !== 0) {
                            return $comparison;
                        }
                    }
                }
                return 0; // If no secondary field to sort by, consider them equal
            }
            return strcmp($a['type'], $b['type']);
        };

        // Sort the entire array of records
        usort($dnsRecords, $recordComparator);

        return $dnsRecords;
    }

    public function checkDnsTxtRecord($url, $teamCollectionKey)
    {
        $records = dns_get_record($url, DNS_TXT);
        foreach ($records as $record) {
            if (isset($record['txt']) && strpos($record['txt'], $this->PREFIX . $teamCollectionKey) !== false) {
                return true;
            }
        }
        return false;
    }

    public function verifyDomain($domain, $teamCollectionKey)
    {
        $domain->verified = $this->checkDnsTxtRecord($domain->hostname . '.' . $domain->tld, $teamCollectionKey);
        $domain->save();
    }

    public function gatherAllRecords($domain)
    {
        $url = $domain->hostname . '.' . $domain->tld;
        $dnsTypes = DNS_A + DNS_CNAME + DNS_HINFO + DNS_MX + DNS_NS + DNS_PTR + DNS_TXT + DNS_AAAA + DNS_SRV + DNS_NAPTR;
        $records = dns_get_record($url, $dnsTypes);

        return $this->normalize($records);
    }
}
