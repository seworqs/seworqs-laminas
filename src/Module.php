<?php

declare(strict_types=1);

namespace Seworqs\Laminas;

use Doctrine\DBAL\Types\Type as DBALType;
use Laminas\Mvc\MvcEvent;
use Seworqs\Laminas\Doctrine\DBAL\Types\UTCDateTimeType;
use Seworqs\Laminas\Listener\AclListener;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function onBootstrap(MvcEvent $event): void
    {

        // Check for DBAL type.
        if (! DBALTYPE::hasType('utcdatetime') && class_exists(UTCDateTimeType::class)) {
            // Add type.
            DBALType::addType('utcdatetime', UTCDateTimeType::class);
        }

        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();

        $eventManager->attach(
            MvcEvent::EVENT_DISPATCH,
            function (MvcEvent $e) {
                $match = $e->getRouteMatch();
                if ($match->getParam('isAjax', false)) {
                    $request = $e->getRequest();
                    if (! $request->isXmlHttpRequest()) {
                        $response = $e->getResponse();
                        $response->setContent('Not Found');
                        $response->setStatusCode(404);
                        return $response;
                    }
                }
            },
            100
        );
        $aclListener = new AclListener();
        $sharedEventManager->attach(
            '*',
            MvcEvent::EVENT_DISPATCH,
            [$aclListener, 'onDispatch'],
            100
        );
    }
}
