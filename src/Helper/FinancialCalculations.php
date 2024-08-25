<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Helper;

class FinancialCalculations
{
    static function getInExPercentage($amount, $isInclusive, $percentage, $count = 1)
    {

        if ($isInclusive) {
            $ex = $amount * 100 / (100 + $percentage);
            $in = $amount;
        } else {
            $in = $amount * (100 + $percentage) / 100;
            $ex = $amount;
        }

        $multipliedIn = $in * $count;
        $multipliedEx = $ex * $count;

        return [
            'unit' => [
                'in' => $in,
                'ex' => $ex,
            ],
            'multiplied' => [
                'multiplier' => $count,
                'in' => $multipliedIn,
                'ex' => $multipliedEx,
            ]
        ];
    }
}
