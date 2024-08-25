<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Doctrine\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class RecordDateListener implements EventSubscriber
{

    private $dt;

    public function getSubscribedEvents()
    {
        return [Events::preUpdate, Events::prePersist];
    }

    public function prePersist(LifecycleEventArgs $args)
    {

        $now = $this->getTimestamp();

        $entity = $args->getObject();
        if (method_exists($entity, 'setCreatedOn')) {
            $entity->setCreatedOn($now);
        }
        if (method_exists($entity, 'setUpdatedOn')) {
            $entity->setUpdatedOn($now);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {

        $now = $this->getTimestamp();

        $entity = $args->getObject();
        if (method_exists($entity, 'setUpdatedOn')) {
            $entity->setUpdatedOn($now);
        }
    }

    private function getTimestamp()
    {

        if (!$this->dt) {
            $this->dt = new \DateTime();
        }

        return $this->dt;
    }
}
