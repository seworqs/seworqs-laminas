<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Entity\Trait;

class UnitPrice
{
    /**
     * @ORM\Column (name="unit_price_ex_vat", type="integer", nullable=false, options={"default": 0})
     */
    private $unitPriceExVat;

    /**
     * @ORM\Column (name="unit_price_in_vat", type="integer", nullable=false, options={"default": 0})
     */
    private $unitPriceInVat;

    /**
     * @ORM\Column (name="unit_vat", type="integer", nullable=false, options={"default": 0})
     */
    private $unitVat;

    /**
     * @return mixed
     */
    public function getUnitPriceExVat()
    {
        // Return value.
        return $this->unitPriceExVat;
    }

    /**
     * @param mixed $unitPriceExVat
     * @return UnitPrice
     */
    public function setUnitPriceExVat($unitPriceExVat)
    {

        // Set value.
        $this->unitPriceExVat = $unitPriceExVat;
        // Return.
        return $this;
    }
}
