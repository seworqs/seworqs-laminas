<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Entity\Trait;

trait Email
{
    /**
     * @ORM\Column (name="email_address", type="String", length=200)
     */
    protected $emailAddress;
    /**
     * @ORM\Column (name="email_name", type="String", length=200)
     */
    protected $emailName;

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

    /**
     * @return mixed
     */
    public function getEmailName()
    {
        // Return value.
        return $this->emailName;
    }

    /**
     * @param mixed $emailName
     * @return Email
     */
    public function setEmailName($emailName)
    {

        // Set value.
        $this->emailName = $emailName;
        // Return.
        return $this;
    }

    public function getEmailData()
    {
        return [
            'formatted' => [
                'full' => sprintf('%s <%s>', $this->getEmailName(), $this->getEmailAddress())
            ]
        ];
    }
}
