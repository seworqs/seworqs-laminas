<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Entity\Trait;

trait Address
{
    /**
     * @ORM\Column (name="street_name", type="string", length=256, nullable=false)
     */
    private $streetName;
    /**
     * @ORM\Column (name="building_number", type="integer", nullable=false)
     */
    private $buildingNumber;
    /**
     * @ORM\Column (name="building_number_suffix", type="string", length=25, nullable=true)
     */
    private $buildingNumberSuffix;
    /**
     * @ORM\Column (name="postal_code", type="string", length=25, nullable=true)
     */
    private $postalCode;
    /**
     * @ORM\Column (name="city", type="string", length=100, nullable=true)
     */
    private $city;
    /**
     * @ORM\Column (name="state", type="string", length=100, nullable=true)
     */
    private $state;
    /**
     * @ORM\Column (name="country", type="string", length=100, nullable=true)
     */
    private $country;

    /**
     * @return mixed
     */
    public function getStreetName()
    {
        // Return value.
        return $this->streetName;
    }

    /**
     * @param mixed $streetName
     * @return Address
     */
    public function setStreetName($streetName)
    {

        // Set value.
        $this->streetName = $streetName;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBuidlingNumber()
    {
        // Return value.
        return $this->buidlingNumber;
    }

    /**
     * @param mixed $buidlingNumber
     * @return Address
     */
    public function setBuidlingNumber($buidlingNumber)
    {

        // Set value.
        $this->buidlingNumber = $buidlingNumber;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBuildingNumberSuffix()
    {
        // Return value.
        return $this->buildingNumberSuffix;
    }

    /**
     * @param mixed $buildingNumberSuffix
     * @return Address
     */
    public function setBuildingNumberSuffix($buildingNumberSuffix)
    {

        // Set value.
        $this->buildingNumberSuffix = $buildingNumberSuffix;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        // Return value.
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     * @return Address
     */
    public function setPostalCode($postalCode)
    {

        // Set value.
        $this->postalCode = $postalCode;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        // Return value.
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return Address
     */
    public function setCity($city)
    {

        // Set value.
        $this->city = $city;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        // Return value.
        return $this->state;
    }

    /**
     * @param mixed $state
     * @return Address
     */
    public function setState($state)
    {

        // Set value.
        $this->state = $state;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        // Return value.
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return Address
     */
    public function setCountry($country)
    {

        // Set value.
        $this->country = $country;
        // Return.
        return $this;
    }

    public function getAddressData()
    {
        return [
            'formatted' => [
                'streetAndNumber' => join(' ', [
                    $this->getStreetName(),
                    $this->getBuidlingNumber(),
                    $this->getBuildingNumberSuffix()
                ]),
                'postalCodeAndCity' => join(' ', [$this->getPostalCode(), $this->getCity()]),
            ]
        ];
    }
}
