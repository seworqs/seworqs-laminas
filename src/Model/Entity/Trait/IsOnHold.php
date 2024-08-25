<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Seworqs\Helper\EntityDataHelper;

trait IsOnHold
{
    /**
     * @ORM\Column (name="is_on_hold", type="boolean", options={"default":false})
     */
    protected $isOnHold;

    /**
     * @return mixed
     */
    public function getIsOnHold()
    {
        // Return value.
        return $this->isOnHold;
    }

    /**
     * @param mixed $isOnHold
     * @return IsActive
     */
    public function setIsOnHold($isOnHold)
    {

        // Set value.
        $this->isOnHold = $isOnHold;
        // Return.
        return $this;
    }

    public function getIsOnHoldData()
    {
        return EntityDataHelper::getBooleanData($this->getIsOnHold());
    }
}
