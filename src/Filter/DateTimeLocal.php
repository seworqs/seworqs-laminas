<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Filter;

use Laminas\Filter\AbstractFilter;

class DateTimeLocal extends AbstractFilter
{

    private $timezone;

    public function __construct($options = [])
    {

        // For now. It's an DateTimeZone or a string (representing time zone) or not set (default UTC).
        if (isset($options['timezone'])) {
            if ($options['timezone'] instanceof \DateTimeZone) {
                return $options['timezone'];
            } else {
                return new \DateTimeZone($options['timezone']);
            }
        }

        return new \DateTimeZone('UTC');
    }

    public function filter($value)
    {

        try {
            $dateTime = new \DateTime($value, $this->timezone);

            return $dateTime;
        } catch (\Exception $e) {
            throw new \RuntimeException(t('Invalid date time format.'), 0, $e);
        }
    }
}
