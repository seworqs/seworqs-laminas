<?php

namespace Seworqs\Laminas\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ErrorController extends AbstractActionController
{
    public function errorAction()
    {
        $routeMatch = $this->getEvent()->getRouteMatch();
        $statusCode = $routeMatch->getParam('status');
        $this->getResponse()->setStatusCode($statusCode);
        $viewModel = new ViewModel();
        $viewModel->setTemplate('error/' . (string)$statusCode);
        return $viewModel;
    }
}
