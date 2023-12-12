<?php

namespace App\Services;

use App\Models\Domain;

class DomainScoreService
{
    public function score($domainId)
    {
        $score = 0;
        $domain = Domain::where('id', $domainId)->first();

        if ($domain->verified) {
            // Domains over 20 characters are essentially valueless. Not sorry. - Sam
            $score += (strlen($domain->hostname) >= 20) ? 0 : 20 - strlen($domain->hostname);

            switch ($domain->tld) {
                case "com":
                    $score += 100;
                    break;
                case "org":
                    $score += 10;
                    break;
                case "net":
                    $score += 25;
                    break;
                case "io":
                    $score += 75;
                    break;
                case "foundation":
                    $score += 5;
                    break;
            }
        }

        $domain->score = $score;
        $domain->save();

        return $score;
    }
}
