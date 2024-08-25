<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Listener;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Sql;
use Laminas\Http\Response;
use Laminas\Mvc\MvcEvent;
use Laminas\Session\Container;
use Seworqs\Laminas\Model\Account\AccountService;
use Seworqs\Laminas\Permission\Acl;
use Seworqs\Laminas\Service\CurrentUserService;

class AclListener
{
    public function onDispatch(MvcEvent $event)
    {

        $userRole = Acl::ROLE_GUEST;

        $serviceManager = $event->getApplication()->getServiceManager();

        $config = $serviceManager->get('config');

        $currentUser = $serviceManager->get(CurrentUserService::class);

        if (isset($config['seworqs']['acl']['enabled']) && $config['seworqs']['acl']['enabled']) {

            if ($currentUser->getAccountID()) {

                /**
                 * @var AccountService $accountService
                 */
                $accountService = $serviceManager->get(AccountService::class);
                $account = $accountService->findByID($currentUser->getAccountID());

                if ($account && $account->getUserRole()) {
                    $currentUser->setAccountData($account);
                } else {
                    $currentUser->resetData();
                }
            }

            $acl = $event->getApplication()->getServiceManager()->get(Acl::class);

            $routeMatch = $event->getRouteMatch();

            $controller = $routeMatch->getParam('controller');
            $action = $routeMatch->getParam('action');

            if (
                ! $acl->hasRole($currentUser->getRole())
                || ! $acl->hasResource($controller)
                || ! $acl->isAllowed($currentUser->getRole(), $controller, $action)
            ) {
                $router = $event->getRouter();

                $path = $router->assemble(['status' => 403], ['name' => 'error']);

                $response = $event->getResponse() ?: new Response();
                $response->getHeaders()->addHeaderLine('Location', $path);
                $response->setStatusCode(Response::STATUS_CODE_302);

                $event->stopPropagation(true);

                return $response;
            }
        }
    }
}
