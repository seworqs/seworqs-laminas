<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Service;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\ServiceManager;

class AbstractService
{
    private $serviceManager;
    private $entityManager;

    public function __construct(ServiceManager $serviceManager, EntityManager $entityManager)
    {
        $this->serviceManager = $serviceManager;
        $this->entityManager = $entityManager;
    }

    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
