<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Controller;

use Laminas\View\Model\JsonModel;
use Seworqs\Laminas\Service\MenuStateService;

class MenuController extends BaseActionController
{

    public function ajaxClickAction()
    {

        $session = $this->getServiceManager()->get('SeworqsSession');
        $menuService = $this->getServiceManager()->get(MenuStateService::class);

        // Get action from url.
        $act = $this->params()->fromRoute('click');
        $item = $this->params()->fromRoute('item');

        // Set menu state.
        $menuService->setItemExpanded($item, $act === 'show');

        $result = [
            "code" => "200",
            "description" => "Menu state saved."
        ];

        return new JsonModel($result);
    }
}
