<?php

declare(strict_types=1);

namespace Seworqs\Laminas\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class DateTime extends AbstractHelper
{

    /**
     * @var \DateTime $dt
     */
    protected $dt;

    public function __invoke(\DateTime|null $dateTime)
    {

        if ($dateTime) {
            $this->dt = $dateTime;
        }
        return $this;
    }

    public function setTimeZone($timezone = 'UTC')
    {
        $timezone = new \DateTimeZone($timezone);
        if ($this->dt) {
            $this->dt->setTimezone($timezone);
        }
        return $this;
    }

    public function toString($format = 'Y-m-d H:i')
    {
        return $this->dt ? $this->dt->format($format) : '';
    }
}
