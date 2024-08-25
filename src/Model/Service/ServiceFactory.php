<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\Containerinterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceManager;

class ServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $sm = $container->get(ServiceManager::class);
        $em = $container->get(EntityManager::class);
        return new $requestedName($sm, $em);
    }
}
