<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Account;

use Seworqs\Laminas\Model\Repository\EntityRepository;

class AccountRepository extends EntityRepository
{
    public function findByUserName($username)
    {
        return $this->findBy(['UserName' => $username]);
    }
}
