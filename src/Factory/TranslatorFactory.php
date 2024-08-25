<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Factory;

use Interop\Container\Containerinterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Seworqs\Laminas\I18n\Translator\Translator;

class TranslatorFactory implements FactoryInterface
{
    public function __invoke(Containerinterface $container, $requestedName, array $options = null)
    {

        $cache = $container->get('SeworqsCache');

        if ($cache->hasItem('translator')) {
            $translator = $cache->getItem('SeworqsCache');

            return $translator;
        } else {
            $translator = new Translator();
            $cache->setItem('translator', $translator);

            return $translator;
        }
    }
}
