<?php

declare(strict_types=1);

use Seworqs\Laminas\I18n\Translator\Translator;

function getTranslator()
{
    /**
     * @var Translator $translator;
     */
    static $translator;

    if (! $translator) {
        $translator = new Translator();
    }

    // Return.
    return $translator;
}

function t($message, $params = [], $locale = null)
{
    return tc('default', $message, $params, $locale);
}

function tc($context, $message, $params = [], $locale = null)
{
    $translator = getTranslator();
    $translated = $translator->translate($message, $context, $locale);

    if (! empty($params)) {
        $translated = vsprintf($translated, $params);
    }

    return $translated;
}

function t2($singular, $plural, $number, $context = 'default', $locale = null)
{
    $translator = getTranslator();
    $translated = $translator->translatePlural($singular, $plural, $number, $context = 'default', $locale = null);

    return $translated;
}
