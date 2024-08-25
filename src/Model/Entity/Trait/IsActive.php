<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Seworqs\Helper\EntityDataHelper;

trait IsActive
{
    /**
     * @ORM\Column (name="is_active", type="boolean", options={"default":false})
     */
    protected $isActive;

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        // Return value.
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     * @return IsActive
     */
    public function setIsActive($isActive)
    {

        // Set value.
        $this->isActive = $isActive;
        // Return.
        return $this;
    }

    public function getIsActiveData()
    {
        return EntityDataHelper::getBooleanData($this->getIsActive());
    }
}
