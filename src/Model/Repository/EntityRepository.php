<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Repository;

use Doctrine\ORM\EntityRepository as ORMEntityRepository;

class EntityRepository extends ORMEntityRepository
{
    //    public function save($object, $flush = false)
    //    {
    //
    //        $em = $this->getEntityManager();
    //        $em->persist($object);
    //        if ($flush) {
    //            $em->flush();
    //        }
    //    }
}
