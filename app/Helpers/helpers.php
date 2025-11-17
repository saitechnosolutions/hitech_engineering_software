<?php

use Carbon\Carbon;


function getFinancialYear() {
    $year = date('Y');  // current year, e.g., 2025
    $month = date('n'); // numeric month 1-12

    if ($month >= 4) { // financial year starts in April
        $start = $year % 100; // last two digits
        $end = ($year + 1) % 100;
    } else {
        $start = ($year - 1) % 100;
        $end = $year % 100;
    }

    return sprintf("%02d-%02d", $start, $end);
}

function formatDate($date)
{
    return $date ? Carbon::parse($date)->format('d-m-Y') : '-';
}

function removeUnderscoreText($text)
{
    return ucwords(str_replace('_', ' ', $text));
}

function removeSpaceAddHyphen($string) {
    return str_replace(' ', '-', $string);
}
