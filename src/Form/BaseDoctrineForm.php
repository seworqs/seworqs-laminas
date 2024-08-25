<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Form;

use Doctrine\Laminas\Hydrator\DoctrineObject;
use Doctrine\ORM\EntityManager;
use Laminas\Hydrator\HydratorInterface;
use Seworqs\Laminas\Form\BaseForm;
use Seworqs\Laminas\Service\CurrentUserService;


abstract class BaseDoctrineForm extends BaseForm
{
    public function __construct(HydratorInterface $hydrator, CurrentUserService $currentUser, $atts = [], $options = [])
    {

        parent::__construct($currentUser, $atts, $options);

        $this->setHydrator($hydrator);
    }
}
