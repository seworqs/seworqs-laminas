<?php

declare(strict_types=1);

namespace Seworqs\Laminas\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\Form\View\Helper\AbstractHelper;

class FormElementHelpOrErrors extends AbstractHelper
{
    public function __invoke(ElementInterface $element)
    {
        $messages = $element->getMessages();
        if (! empty($messages)) {
            return '<div class="form-text text-danger">' . $this->getView()->formElementErrors($element) . '</div>';
        }

        $helpText = $element->getOption('help_block');

        if ($helpText) {
            return '<div class="form-text">' . $this->getView()->escapeHtml($helpText) . '</div>';
        }

        return null;
    }
}
