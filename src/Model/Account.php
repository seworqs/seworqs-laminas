<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Seworqs\Laminas\Model\Entity\Trait\IsActive;
use Seworqs\Laminas\Model\Entity\Trait\ID;
use Seworqs\Laminas\Model\Entity\Trait\RecordDates;

/**
 * @ORM\Entity (repositoryClass="Seworqs\Laminas\Model\Account\AccountRepository")
 * @ORM\Table(name="seworqs_account")
 */
class Account
{
    use ID;
    use IsActive;
    use RecordDates;

    /**
     * @ORM\Column (name="user_name", type="String", length=100, nullable=false)
     */
    private $UserName;
    /**
     * @ORM\Column (name="user_display_name", type="String", length=100, nullable=false)
     */
    private $UserDisplayName;
    /**
     * @ORM\Column (name="password", type="String", length=255, nullable=false)
     */
    private $Password;
    /**
     * @ORM\Column (name="user_role", type="String", length=25, nullable=false)
     */
    private $UserRole;

    /**
     * @ORM\Column (name="is_blocked", type="boolean", options={"default": false})
     */
    private $isBlocked;
    /**
     * @ORM\Column (name="blocked_on", type="utcdatetime", nullable=true)
     */
    private $BlockedOn;
    /**
     * @ORM\Column (name="blocked_reason", type="String", length=100, nullable=true)
     */
    private $BlockedReason;
    /**
     * @ORM\Column (name="last_login_on", type="utcdatetime", nullable=true)
     */
    private $LastLoginOn;
    /**
     * @ORM\Column (name="failed_login_attempts", type="integer", options={"unsigned":true, "default":0})
     */
    private $FailedLoginAttempts;


    /**
     * @ORM\Column (name="timezone", type="String", length=100, nullable=false, options={"default": "UTC"})
     */
    private $Timezone;

    /**
     * @ORM\Column (name="date_format", type="String", length=100, nullable=false, options={"default": "Y-m-d"})
     */
    private $DateFormat;

    /**
     * @ORM\Column (name="time_format", type="String", length=100, nullable=false, options={"default": "H:i"})
     */
    private $TimeFormat;

    /**
     * @ORM\Column (name="date_time_format", type="String", length=100, nullable=false, options={"default": "Y-m-d H:i"})
     */
    private $DateTimeFormat;

    /**
     * @ORM\Column (name="locale", type="String", length=25, nullable=false, options={"default": "nl_NL"})
     */
    private $Locale;




    public function __construct()
    {

        $uuid = Uuid::uuid4();

        // Set defaults.
        $this->setFailedLoginAttempts(0)
            ->setLastLoginOn(null)
            ->setBlockedReason(null)
            ->setBlockedOn(null)
            ->setIsActive(false)
            ->setIsBlocked(false)
            ->setUpdatedOn(null)
            ->setCreatedOn(null)
            ->setDeletedOn(null)
            ->setPassword(password_hash($uuid->toString(), PASSWORD_BCRYPT, ['cost' => 12]))
            ->setUserDisplayName(null)
            ->setUserName(null)
            ->setUserRole(null)
            ->setTimezone('UTC')
            ->setDateFormat('Y-m-d')
            ->setTimeFormat('H:i')
            ->setDateTimeFormat('Y-m-d H:i')
            ->setLocale('en_US');
    }


    /**
     * @return mixed
     */
    public function getUserName()
    {
        // Return value.
        return $this->UserName;
    }

    /**
     * @param mixed $UserName
     * @return Account
     */
    public function setUserName($UserName)
    {

        // Set value.
        $this->UserName = $UserName;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserDisplayName()
    {
        // Return value.
        return $this->UserDisplayName;
    }

    /**
     * @param mixed $UserDisplayName
     * @return Account
     */
    public function setUserDisplayName($UserDisplayName)
    {

        // Set value.
        $this->UserDisplayName = $UserDisplayName;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        // Return value.
        return $this->Password;
    }

    /**
     * @param mixed $Password
     * @return Account
     */
    public function setPassword($Password)
    {

        // Set value.
        $this->Password = $Password;
        // Return.
        return $this;
    }


    /**
     * @return mixed
     */
    public function getIsBlocked()
    {
        // Return value.
        return $this->isBlocked;
    }

    /**
     * @param mixed $isBlocked
     * @return Account
     */
    public function setIsBlocked($isBlocked)
    {

        // Set value.
        $this->isBlocked = $isBlocked;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBlockedOn()
    {
        // Return value.
        return $this->BlockedOn;
    }

    /**
     * @param mixed $BlockedOn
     * @return Account
     */
    public function setBlockedOn($BlockedOn)
    {

        // Set value.
        $this->BlockedOn = $BlockedOn;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBlockedReason()
    {
        // Return value.
        return $this->BlockedReason;
    }

    /**
     * @param mixed $BlockedReason
     * @return Account
     */
    public function setBlockedReason($BlockedReason)
    {

        // Set value.
        $this->BlockedReason = $BlockedReason;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastLoginOn()
    {
        // Return value.
        return $this->LastLoginOn;
    }

    /**
     * @param mixed $LastLoginOn
     * @return Account
     */
    public function setLastLoginOn($LastLoginOn)
    {

        // Set value.
        $this->LastLoginOn = $LastLoginOn;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFailedLoginAttempts()
    {
        // Return value.
        return $this->FailedLoginAttempts;
    }

    /**
     * @param mixed $FailedLoginAttempts
     * @return Account
     */
    public function setFailedLoginAttempts($FailedLoginAttempts)
    {

        // Set value.
        $this->FailedLoginAttempts = $FailedLoginAttempts;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserRole()
    {
        // Return value.
        return $this->UserRole;
    }

    /**
     * @param mixed $UserRole
     * @return Account
     */
    public function setUserRole($UserRole)
    {

        // Set value.
        $this->UserRole = $UserRole;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimezone()
    {
        // Return value.
        return $this->Timezone;
    }

    /**
     * @param mixed $Timezone
     * @return Account
     */
    public function setTimezone($Timezone)
    {

        // Set value.
        $this->Timezone = $Timezone;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateFormat()
    {
        // Return value.
        return $this->DateFormat;
    }

    /**
     * @param mixed $DateFormat
     * @return Account
     */
    public function setDateFormat($DateFormat)
    {

        // Set value.
        $this->DateFormat = $DateFormat;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimeFormat()
    {
        // Return value.
        return $this->TimeFormat;
    }

    /**
     * @param mixed $TimeFormat
     * @return Account
     */
    public function setTimeFormat($TimeFormat)
    {

        // Set value.
        $this->TimeFormat = $TimeFormat;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateTimeFormat()
    {
        // Return value.
        return $this->DateTimeFormat;
    }

    /**
     * @param mixed $DateTimeFormat
     * @return Account
     */
    public function setDateTimeFormat($DateTimeFormat)
    {

        // Set value.
        $this->DateTimeFormat = $DateTimeFormat;
        // Return.
        return $this;
    }

    public function getLocale()
    {
        // Return value.
        return $this->Locale;
    }

    public function setLocale($Locale)
    {

        // Set value.
        $this->Locale = $Locale;
        // Return.
        return $this;
    }
}
