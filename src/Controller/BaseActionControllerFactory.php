<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Controller;

use Doctrine\ORM\EntityManager;
use Interop\Container\Containerinterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Form\FormElementManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Session\Container;
use Seworqs\Laminas\Service\CurrentUserService;

class BaseActionControllerFactory implements FactoryInterface
{
    public function __invoke(Containerinterface $container, $requestedName, array $options = null)
    {

        $config = $container->get('config');

        $entityManager = $container->get(EntityManager::class);

        $serviceManager = $container->get(ServiceManager::class);

        $currentUserService = $container->get(CurrentUserService::class);

        $formElementManager = $container->get(FormElementManager::class);

        return new $requestedName($entityManager, $serviceManager, $currentUserService, $formElementManager);
    }
}
