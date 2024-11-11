<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Entity\Trait;

trait EmailAddress
{
    /**
     * @ORM\Column (name="email_address", type="String", length=200)
     */
    protected $emailAddress;

    /**
     * @return mixed
     */
    public function getEmailAddress()
    {
        // Return value.
        return $this->emailAddress;
    }

    /**
     * @param mixed $emailAddress
     * @return EmailAddress
     */
    public function setEmailAddress($emailAddress)
    {

        // Set value.
        $this->emailAddress = $emailAddress;
        // Return.
        return $this;
    }

    public function getEmailAddressData()
    {
        return [
            'formatted' => $this->getEmailAddress()
        ];
    }
}
