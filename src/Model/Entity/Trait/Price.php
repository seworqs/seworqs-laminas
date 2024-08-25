<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Entity\Trait;

use Money\Money;
use Money\Number;
use Seworqs\Laminas\Helper\EntityDataHelper;
use Seworqs\Laminas\Helper\FinancialCalculations;

trait Price
{
    /**
     * @ORM\Column (name="price", type="integer", nullable=false, options={"default": 0})
     */
    private $price;
    /**
     * @ORM\Column (name="is_in_vat", type="boolean", options={"default": 0})
     */
    private $isInVat;
    /**
     * @ORM\Column (name="vat_percentage", type="integer", nullable=false, option={"default": 0})
     */
    private $vatPercentage;

    /**
     * @return mixed
     */
    public function getPrice()
    {
        // Return value.
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return Price
     */
    public function setPrice($price)
    {

        // Set value.
        $this->price = $price;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsInVat()
    {
        // Return value.
        return $this->isInVat;
    }

    /**
     * @param mixed $isInVat
     * @return Price
     */
    public function setIsInVat($isInVat)
    {

        // Set value.
        $this->isInVat = $isInVat;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVatPercentage()
    {
        // Return value.
        return $this->vatPercentage;
    }

    /**
     * @param mixed $vatPercentage
     * @return Price
     */
    public function setVatPercentage($vatPercentage)
    {

        // Set value.
        $this->vatPercentage = $vatPercentage;
        // Return.
        return $this;
    }

    public function getPriceData($currency = 'EUR', $locale = 'nl_NL')
    {

        //        $inExCalc = FinancialCalculations::getInExPercentage($this->getPrice(), $this->getIsInVat(),
        //            $this->getVatPercentage(), 1);


        return EntityDataHelper::getCurrencyData($this->getPrice(), $locale, $currency);
    }
}
