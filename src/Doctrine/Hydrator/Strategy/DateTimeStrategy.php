<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Doctrine\Hydrator\Strategy;

use Laminas\Hydrator\Strategy\StrategyInterface;

class DateTimeStrategy implements StrategyInterface
{

    private $userTimezone;
    private $utcTimezone;

    public function __construct($userTimezone)
    {
        $this->userTimezone = new \DateTimeZone($userTimezone);
        $this->utcTimezone = new \DateTimeZone('UTC');
    }

    public function hydrate($value, ?array $data)
    {
        if ($value === null || $value === '') {
            return null;
        }

        $dateTime = new \DateTime($value, $this->utcTimezone);
        $dateTime->setTimezone($this->userTimezone);
        return $dateTime;
    }

    public function extract($value, ?object $object = null)
    {
        if (!$value instanceof \DateTime) {
            return $value;
        }
        $value->setTimezone($this->userTimezone);
        return $value;
    }
}
