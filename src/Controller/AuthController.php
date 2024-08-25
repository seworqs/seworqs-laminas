<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Controller;

use Doctrine\ORM\EntityManager;
use Laminas\Validator\Csrf;
use Laminas\View\Model\ViewModel;
use Seworqs\Laminas\Form\Login;
use Seworqs\Laminas\Form\Registration;
use Seworqs\Laminas\Model\Account;
use Seworqs\Laminas\Model\Account\AccountService;
use Seworqs\Laminas\Model\Account\LoginError;
use Seworqs\Laminas\Permission\Acl;
use Seworqs\Laminas\Service\CurrentUserService;
use Seworqs\Laminas\Validator\UniqueUserNameValidator;

class AuthController extends BaseActionController
{
    public function registerAction()
    {

        $this->layout('seworqs/register');

        /**
         * @var AccountService $accountService
         */
        $accountService = $this->getServiceManager()->get(AccountService::class);
        $entityManager = $this->getEntityManager();

        $account = $accountService->createNew();

        //$form = new Registration($entityManager, 'frmRegistration');
        $form = $this->getFormElementManager()->get(Registration::class, [
            'name' => 'frmRegistration',
            'method' => 'POST',
            'action' => $this->url()->fromRoute('logout')
        ]);
        // $form->setAttribute('action', $this->url()->fromRoute('register'));
        // $form->setAttribute('method', 'post');

        $form->bind($account);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            $userNameValidator = $form->getInputFilter()
                ->get('UserName')
                ->getValidatorChain()
                ->attach(new UniqueUserNameValidator(['accountService' => $accountService, 'currentAccount' =>
                $account]));

            if ($form->isValid()) {
                $now = new \DateTime();
                /**
                 * @var Account $account
                 */
                $account = $form->getData();

                // Add other data.
                $account->setPassword(password_hash($form->get('ConfirmPassword')->getValue(), PASSWORD_BCRYPT, ['cost' => 12]));
                $account->setUserRole(Acl::ROLE_CUSTOMER);
                $account->setCreatedOn($now);
                $account->setUpdatedOn($now);
                $account->setIsActive(true);

                // Save.
                $entityManager->persist($account);
                $entityManager->flush();

                $this->flashmessenger()->addInfoMessage(t('Registration completed!'));

                return $this->redirect()->toRoute('login');
            } else {
                //                if (key_exists(UniqueUserNameValidator::USERNAME_EXISTS, $userNameValidator->getMessages())) {
                //                    $this->flashmessenger()->addErrorMessage(t('Already an account? Try to login!'));
                //                }

                $csrf = $form->getInputFilter()
                    ->get('csrf')
                    ->getValidatorChain();

                if (key_exists(Csrf::NOT_SAME, $csrf->getMessages())) {
                    $this->flashmessenger()->addErrorMessage(t('Invalid form. Try again.'));
                }
            }
        }

        // Return.
        return new ViewModel([
            'frm' => $form
        ]);
    }

    public function loginAction()
    {
        $this->layout('seworqs/login');

        $user = null;

        //$form = new Login('frmLogin');
        $form = $this->getFormElementManager()->get(Login::class, [
            'name' => 'frmLogin',
            'method' => 'POST',
            'action' => $this->url()->fromRoute('login')
        ]);
        //$form->setAttribute('action', );
        //$form->setAttribute('method', 'post');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {

                /**
                 * @var AccountService $accountService;
                 * @var entityManager $entityManager;
                 */
                $accountService = $this->getServiceManager()->get(AccountService::class);
                $entityManager = $this->getEntityManager();

                $account = $accountService->loginUser(
                    $form->get('UserName')->getValue(),
                    $form->get('Password')->getValue(),
                    true
                );

                if ($account instanceof Account) {
                    return $this->redirect()->toRoute('home');
                } else {
                    switch ($account) {
                        case LoginError::Blocked:
                            $this->flashMessenger()->addWarningMessage(t('Your account is blocked.'));
                            break;
                        case LoginError::NoAccount:
                        case LoginError::LoginError:
                        default:
                            $this->flashMessenger()->addWarningMessage(t('We could not log you in.'));
                            break;
                    }
                }
            }
        }

        return new ViewModel(['frm' => $form]);
    }

    public function logoutAction()
    {

        $accountService = $this->getServiceManager()->get(AccountService::class);

        $accountService->logoutUser();

        // TODO Make redirect dynamic (config setting)
        return $this->redirect()->toRoute('home');
    }
}
