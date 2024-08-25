<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Seworqs\Helper\EntityDataHelper;

trait ID
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column (name="id", type="integer")
     */
    private $ID;

    /**
     * @return mixed
     */
    public function getID()
    {
        // Return value.
        return $this->ID;
    }

    /**
     * @param mixed $ID
     * @return ID
     */
    public function setID($ID)
    {

        // Set value.
        $this->ID = $ID;
        // Return.
        return $this;
    }

    public function getIDData()
    {
        return EntityDataHelper::getNumericData($this->getID());
    }
}
