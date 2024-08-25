<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Account;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Session\Container;
use Seworqs\Laminas\Doctrine\EventListener\RecordDateListener;
use Seworqs\Laminas\Model\Account;
use Seworqs\Laminas\Model\Service\AbstractService;
use Seworqs\Laminas\Service\CurrentUserService;

enum LoginError: string
{
    case NoAccount = 'no_account';
    case LoginError = 'login_error';
    case Blocked = 'blocked';
}

class AccountService extends AbstractService
{
    const BLOCKED_MAX_FAILURE_ATTEMPTS = 'max_failures';
    const BLOCKED_OTHER = 'other';

    private $accountRepository;

    public function __construct(ServiceManager $serviceManager, EntityManager $entityManager)
    {
        parent::__construct($serviceManager, $entityManager);

        $this->accountRepository = $entityManager->getRepository(Account::class);
    }

    public function createNew()
    {
        return new Account();
    }

    public function isUniqueName($name, Account $currentAccount = null)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(u.ID)')
            ->from(Account::class, 'u')
            ->where('u.UserName = :userName')
            ->setParameter('userName', $name);

        if ($currentAccount and $currentAccount->getID()) {
            $qb->andWhere('u.id != :currentAccount')
                ->setParameter('currentAccount', $currentAccount);
        }

        return ! $qb->getQuery()->getSingleScalarResult();
    }

    public function findByID($id)
    {
        return $this->accountRepository->find($id);
    }

    public function findByUserName($userName)
    {
        return $this->accountRepository->findOneBy(['UserName' => $userName]);
    }

    public function logSuccessfulLogin(Account $account, \DateTime|null $datetime = null)
    {

        if (! $datetime) {
            $datetime = new \DateTime();
        }

        $account->setLastLoginOn($datetime);
        $account->setFailedLoginAttempts(0);
    }

    public function logFailedLoginAttempt(Account $account, \DateTime|null $datetime = null)
    {

        if (! $datetime) {
            $datetime = new \DateTime();
        }

        $newCount = $account->getFailedLoginAttempts() + 1;

        $account->setFailedLoginAttempts($newCount);

        if ($newCount >= 3) {
            self::blockAccount($account, self::BLOCKED_MAX_FAILURE_ATTEMPTS);
        }
    }

    public function blockAccount(Account $account, $reason = null, \DateTime|null $dateTime = null)
    {

        if (! $dateTime) {
            $dateTime = new \DateTime();
        }

        $account->setIsBlocked(true);
        $account->setBlockedOn($dateTime);
        if ($reason && is_string($reason)) {
            $account->setBlockedReason($reason);
        }
    }

    public function deblockAccount(Account $account, \DateTime|null $datetime = null)
    {

        if (! $datetime) {
            $datetime = new \DateTime();
        }

        $account->setIsBlocked(false);
        $account->setBlockedOn(null);
        $account->setBlockedReason(null);
    }

    public function loginUser($username, $password, $save = false): Account|LoginError
    {

        // Get current user.
        $currentUser = $this->getServiceManager()->get(CurrentUserService::class);

        /**
         * @var Account $account
         */
        try {
            $account = $this->findByUserName($username);
        } catch (\Exception $e) {
            // We'll catch it later.
            $account = null;
        }

        if ($account && $account->getIsBlocked()) {
            // Already blocked.
            return LoginError::Blocked;
        } elseif ($account && password_verify($password, $account->getPassword())) {

            // Set account data in current user (= logged in).
            $currentUser->setAccountData($account);

            $this->logSuccessfulLogin($account);

            if ($save) {

                //                $em = $this->getEntityManager();
                //                $em->getEventManager()->addEventSubscriber(new RecordDateListener());
                //
                //                $em->persist($account);
                //                $em->flush($account);

                // We save it here. Just to be sure.
                $this->getEntityManager()->persist($account);
                $this->getEntityManager()->flush($account);
            }

            return $account;
        } elseif ($account) {
            $this->logFailedLoginAttempt($account);

            if ($save) {
                // We save it directly, we only return the error.
                $this->getEntityManager()->persist($account);
                $this->getEntityManager()->flush($account);
            }

            return LoginError::LoginError;
        } else {
            // Nothing to save.
            return LoginError::NoAccount;
        }
    }

    public function logoutUser()
    {

        // Get current user.
        $currentUser = $this->getServiceManager()->get(CurrentUserService::class);

        // Reset data. (to default values)
        $currentUser->resetData(true);
    }

    //    protected function setRecordDates(Account $account, \DateTime|null $dateTime) {
    //
    //        if (!$dateTime) {
    //            $now = $dateTime;
    //        } else {
    //            $now = new \DateTime();
    //        }
    //
    //        if (!$account->getCreatedOn()) {
    //            $account->setCreatedOn($now);
    //        }
    //        $account->setUpdatedOn($now);
    //    }
}
