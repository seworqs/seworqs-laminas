<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Service;

use Laminas\Session\Container;

class MenuStateService
{

    private $session;

    public function __construct(Container $session)
    {
        $this->session = $session;

        if (!$this->session->offsetExists('menu')) {
            $this->resetData();
        }
    }

    public function getSession()
    {
        return $this->session;
    }

    public function resetData()
    {
        $this->setMenuData([]);
    }

    public function getMenuPreferences()
    {
        return $this->session->offsetGet('menu');
    }

    public function setMenuData($menu = [])
    {
        $this->session->offsetSet('menu', $menu);
    }

    public function isItemCollapsed($item)
    {

        // Get menu preferences from session.
        $menu = $this->getMenuPreferences();

        // Return.
        return array_key_exists($item, $menu) ? !$menu[$item] : true;
    }

    public function isItemExpanded($item)
    {

        // Get menu preferences from session.
        $menu = $this->getMenuPreferences();

        // Return.
        return array_key_exists($item, $menu) ? $menu[$item] : false;
    }

    public function setItemCollapsed($item, $collapsed = true)
    {

        // Get menu preferences from session.
        $menu = $this->getMenuPreferences();

        // Set collapsed.
        $menu[$item] = !$collapsed;

        // Set.
        $this->session->offsetSet('menu', $menu);

        // Return.
        return $this;
    }

    public function setItemExpanded($item, $expanded = true)
    {

        // Get menu preferences from session.
        $menu = $this->getMenuPreferences();

        // Set collapsed.
        $menu[$item] = $expanded;

        // Set.
        $this->session->offsetSet('menu', $menu);

        // Return.
        return $this;
    }
}
