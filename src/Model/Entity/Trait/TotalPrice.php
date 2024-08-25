<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Entity\Trait;

class TotalPrice
{
    /**
     * @ORM\Column (name="total_price_ex_vat", type="integer", nullable=false, options={"default": 0})
     */
    private $totalPriceExVat;

    /**
     * @ORM\Column (name="total_price_in_vat", type="integer", nullable=false, options={"default": 0})
     */
    private $totalPriceInVat;

    /**
     * @ORM\Column (name="total_vat", type="integer", nullable=false, options={"default": 0})
     */
    private $totalVat;

    /**
     * @return mixed
     */
    public function getTotalPriceExVat()
    {
        // Return value.
        return $this->totalPriceExVat;
    }

    /**
     * @param mixed $totalPriceExVat
     * @return TotalPrice
     */
    public function setTotalPriceExVat($totalPriceExVat)
    {

        // Set value.
        $this->totalPriceExVat = $totalPriceExVat;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalPriceInVat()
    {
        // Return value.
        return $this->totalPriceInVat;
    }

    /**
     * @param mixed $totalPriceInVat
     * @return TotalPrice
     */
    public function setTotalPriceInVat($totalPriceInVat)
    {

        // Set value.
        $this->totalPriceInVat = $totalPriceInVat;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalVat()
    {
        // Return value.
        return $this->totalVat;
    }

    /**
     * @param mixed $totalVat
     * @return TotalPrice
     */
    public function setTotalVat($totalVat)
    {

        // Set value.
        $this->totalVat = $totalVat;
        // Return.
        return $this;
    }
}
