<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Helper;

use Carbon\Carbon;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

class EntityDataHelper
{
    const BOOL_ACTIVE = 'active';
    const BOOL_BINAIR = 'binair';
    const BOOL_ONOFF = 'onoff';
    const BOOL_RIGHTWRONG = 'rightwrong';
    const BOOL_TRUEFALSE = 'truefalse';
    const BOOL_YESNO = 'yesno';

    const NUMERIC_NUMBER = 'number';

    const CURR_COMPLETE = 'complete';
    const CURR_SYMBOL = 'symbol';
    const CURR_CODE = 'code';

    static function getBooleanData($boolean)
    {

        $boolean = (bool)$boolean;

        return [
            'raw' => $boolean,
            'formatted' => [
                self::FORMATTED_ACTIVE => $boolean ? tc('Boolean', 'Active') : tc('Boolean', 'Inactive'),
                self::FORMATTED_BINAIR => $boolean ? 1 : 0,
                self::FORMATTED_ONOFF => $boolean ? tc('Boolean', 'On') : tc('Boolean', 'Off'),
                self::FORMATTED_RIGHTWRONG => $boolean ? tc('Boolean', 'Right') : tc('Boolean', 'Wrong'),
                self::FORMATTED_TRUEFALSE => $boolean ? tc('Boolean', 'True') : tc('Boolean', 'False'),
                self::FORMATTED_YESNO => $boolean ? tc('Boolean', 'Yes') : tc('Boolean', 'No'),
            ]
        ];
    }

    static function getNumericData($number, $locale = "en_US", $fractionDigits = 2)
    {
        $result = [
            'raw' => $number,
            'formatted' => []
        ];

        $nf = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
        $nf->setAttribute(\NumberFormatter::FRACTION_DIGITS, $fractionDigits);

        if (is_numeric($number)) {
            $result['formatted'] = [
                self::NUMERIC_NUMBER  => $nf->format($number)
            ];
        }

        return $result;
    }

    static function getCurrencyData($number, $locale = "en_US", $currency = "USD")
    {

        // Helpers.
        $cf = new \NumberFormatter($locale . '@currency=' . $currencyCode, \NumberFormatter::CURRENCY);

        // Get numeric results.
        $result = self::getNumericData($number, $locale, 2);

        if (is_numeric($number)) {
            // Add currency results.
            $result['complete'] = $cf->formatCurrency($number, $currencyCode);
        } else {
            // Add currency results.
            $result['complete'] = null;
        }

        // Currency code and symbol.
        $result['currency']['code']   = $currencyCode;
        $result['currency']['symbol'] = $cf->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);

        // Return.
        return $result;
    }

    static function getCurrencyDataFromIntegerAsCents(int $number, $locale = "en_US", $currency = 'USD')
    {
        $decimals = $number / 100;

        return self::getCurrencyData($decimals, $locale, $currency);

        //        $money = new Money($number, new Currency($currency));
        //        $currencies = new ISOCurrencies();
        //
        //        $currencyFormatter = new \NumberFormatter($locale . '@currency='. $currency, \NumberFormatter::CURRENCY);
        //        $moneyFormatter = new IntlMoneyFormatter($currencyFormatter, $currencies);
        //
        //        $result = self::getNumericData($number/100, $locale, 2);
        //
        //        if (is_numeric($number)) {
        //            $result['formatted'][self::CURR_CODE] = $currency;
        //            $result['formatted'][self::CURR_SYMBOL] = $currencyFormatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
        //            $result['formatted'][self::CURR_COMPLETE] = $moneyFormatter->format($money);
        //        }
    }


    static function getDateTimeData(\DateTime|null $dateTime, $tz = 'UTC', $locale = 'nl_NL')
    {

        $result = [
            'formatted' => [
                'dateTime' => null,
                'iso8601' => null
            ]
        ];

        $carbon = Carbon::make($dateTime);

        if ($carbon) {
            $carbon->setTimezone($tz)->setLocale($locale);
            $result = [
                'formatted' => [
                    'dateTime' => $carbon->toDateTime(),
                    'iso8601' => $carbon->toIso8601String()
                ]
            ];
        }

        return $result;
    }
}
