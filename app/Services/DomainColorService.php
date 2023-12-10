<?php

namespace App\Services;

class DomainColorService
{
    // Match a TLD with its assigned tailwind background color
    // NOTE: Tailwind does not seem to pickup arbitrary rgb colors in this file...
    public function tldTailwindBg($tld)
    {
        switch ($tld) {
            case 'com':
                return "bg-[#0A961F]";
            case 'org':
                return "bg-[#001E96]";
            case 'net':
                return "bg-[#960A0A]";
            case 'io':
                return "bg-[#AF9A14]";
            case 'foundation':
                return "bg-[#960096]";
        }

        return "bg-[#646464]";
    }

    public function tldToRgb($tld)
    {
        switch ($tld) {
            case 'com':
                return [10, 150, 30];
            case 'org':
                return [0, 30, 150];
            case 'net':
                return [150, 10, 10];
            case 'io':
                return [175, 155, 20];
            case 'foundation':
                return [150, 0, 150];
        }
        // Normalize the string length to at least 3 characters
        $normalizedString = str_pad($tld, 3, $tld);

        // Split the string into three parts
        $len = strlen($normalizedString);
        $partSize = (int)ceil($len / 3);
        $parts = [
            substr($normalizedString, 0, $partSize),
            substr($normalizedString, $partSize, $partSize),
            substr($normalizedString, $partSize * 2)
        ];

        // Function to calculate the sum of ASCII values of characters in a string
        $calculateSum = function ($str) {
            return array_sum(array_map('ord', str_split($str)));
        };

        // Calculate RGB components
        $rgb = array_map($calculateSum, $parts);

        // Normalize each component to be within 0-255
        $rgb = array_map(function ($n) {
            return $n % 256;
        }, $rgb);

        // Convert the RGB components to a hexadecimal color code
        //return sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);

        // Return the three values to be used individually
        return [$rgb[0], $rgb[1], $rgb[2]];
    }
}
