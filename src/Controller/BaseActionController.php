<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Controller;

use Doctrine\ORM\EntityManager;
use Laminas\Form\FormElementManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\ServiceManager\ServiceManager;
use Seworqs\Laminas\Service\CurrentUserService;

class BaseActionController extends AbstractActionController
{
    private $entityManager;
    private $serviceManager;
    private $currentUser;
    private $formElementManager;

    public function __construct(EntityManager $em, ServiceManager $sm, CurrentUserService $currentUser, FormElementManager $formElementManager)
    {
        $this->entityManager = $em;
        $this->currentUser = $currentUser;
        $this->serviceManager = $sm;
        $this->formElementManager = $formElementManager;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    public function getFormElementManager()
    {
        return $this->formElementManager;
    }
}
