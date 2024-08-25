<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\Container;

class SessionFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $config = $container->get('config');

        if (isset($config['seworqs']['session'])) {
            return new Container($config['seworqs']['session']);
        } else {
            return new Container('seworqs');
        }
    }
}
