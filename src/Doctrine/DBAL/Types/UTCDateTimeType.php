<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

class UTCDateTimeType extends DateTimeType
{
    /**
     * @var \DateTimeZone
     */
    private static \DateTimeZone|null $utc = null;

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof \DateTime) {
            $value->setTimezone(self::getUTC());
        }
        return parent::convertToDatabaseValue($value, $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value || $value instanceof \DateTime) {
            return $value;
        }
        $converted = \DateTime::createFromFormat($platform->getDateTimeFormatString(), $value, self::getUTC());
        if (! $converted) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }
        return $converted;
    }

    public function getUTC()
    {

        // Check.
        if (! self::$utc) {
            // Set time zone.
            self::$utc = new \DateTimeZone('UTC');
        }

        // Return.
        return self::$utc;
    }
}
