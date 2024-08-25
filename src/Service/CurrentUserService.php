<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Service;

use Laminas\Session\Container;
use Seworqs\Laminas\Model\Account;
use Seworqs\Laminas\Permission\Acl;

class CurrentUserService
{

    private $session;

    public function __construct(Container $session)
    {
        $this->session = $session;

        if (!$this->getRole()) {
            $this->resetData();
        }
    }

    public function getSession()
    {
        return $this->session;
    }

    public function isLoggedIn()
    {

        // Account role should be set and should not be 'guest'.
        return $this->session->offsetExists('role')
            && $this->session->offsetGet('role') != Acl::ROLE_GUEST;
    }

    public function getAccountID()
    {
        return $this->session->offsetGet('ID');
    }

    public function getName()
    {
        return $this->session->offsetGet('name');
    }

    public function getDisplayName()
    {
        return $this->session->offsetGet('displayName');
    }

    public function getRole()
    {
        return $this->session->offsetGet('role');
    }

    public function getTimezone()
    {
        return $this->session->offsetGet('timezone');
    }

    public function getDateFormat()
    {
        return $this->session->offsetGet('dateFormat');
    }

    public function getTimeFormat()
    {
        return $this->session->offsetGet('timeFormat');
    }

    public function getDateTimeFormat()
    {
        return $this->session->offsetGet('dateTimeFormat');
    }

    public function getLocale()
    {
        return $this->session->offsetGet('locale');
    }

    public function setAccountData(Account $account)
    {

        // Set account data.
        $this->session->offsetSet('ID', $account->getID());
        $this->session->offsetSet('name', $account->getUserName());
        $this->session->offsetSet('displayName', $account->getUserDisplayName());
        $this->session->offsetSet('role', $account->getUserRole());
        $this->session->offsetSet('timezone', $account->getTimezone());
        $this->session->offsetSet('dateFormat', $account->getDateFormat());
        $this->session->offsetSet('timeFormat', $account->getTimeFormat());
        $this->session->offsetSet('dateTimeFormat', $account->getDateTimeFormat());
        $this->session->offsetSet('locale', $account->getLocale());

        // When ever we set account data, we regenerate the session ID.
        $this->session->getManager()->regenerateID();
    }

    public function resetData()
    {

        // Get session manager.
        $manager = $this->session->getManager();

        // Destroy session.
        $manager->destroy([
            'send_expire_cookie' => true,
            'clear_storage'      => true
        ]);

        // Regenerate session ID.
        $manager->regenerateId();

        // Set data to defaults.
        $this->session->offsetSet('ID', null);
        $this->session->offsetSet('name', t('Guest'));
        $this->session->offsetSet('displayName', t('Guest'));
        $this->session->offsetSet('role', Acl::ROLE_GUEST);
        $this->session->offsetSet('timezone', 'Europe/Amsterdam');
        $this->session->offsetSet('dateFormat', 'd-m-Y');
        $this->session->offsetSet('timeFormat', 'H:i');
        $this->session->offsetSet('dateTimeFormat', 'd-m-Y H:i');
        $this->session->offsetSet('locale', 'nl_NL');
    }
}
