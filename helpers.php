<?php

namespace v {
    function time($unix)
    {
        return \Carbon\Carbon::createFromTimestamp($unix)->toDateTimeString();
    }

    function price($amount)
    {
        $amount = number_format($amount / 100, 2, ',', '.') . ' kr.';
        $amount = str_replace(',00', '', $amount);

        return $amount;
    }

    function address($address)
    {
        $line2 = $address->line2 ? "{$address->line2}<br>" : '';

        return "{$address->line1}<br>$line2 {$address->country}-{$address->postal_code} {$address->city}";
    }
}


