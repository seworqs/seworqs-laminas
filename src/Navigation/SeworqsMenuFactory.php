<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Navigation;

use Interop\Container\Containerinterface;
use Laminas\Navigation\Navigation;
use Laminas\Navigation\Service\AbstractNavigationFactory;
use Laminas\ServiceManager\Factory\FactoryInterface;

class SeworqsMenuFactory extends AbstractNavigationFactory implements FactoryInterface
{
    protected function getName()
    {
        return 'main_menu';
    }

    public function __invoke(Containerinterface $container, $requestedName, array $options = null)
    {

        $config = $container->get('config');
        $navigation = new Navigation();
        $pages = [];
        if (isset($config['seworqs']['navigation'][$requestedName])) {
            $pages = $config['navigation'][$requestedName];
        } elseif (isset($config['navigation'][$requestedName])) {
            $pages = $config['navigation'][$requestedName];
        }

        foreach ($pages as $page) {
            $navigation->addPage($page);
        }

        return $navigation;

        //return $this->createService($container);
    }
}
