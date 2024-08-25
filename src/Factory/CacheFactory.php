<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Factory;

use Interop\Container\Containerinterface;
use Laminas\Cache\Service\StorageAdapterFactoryInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class CacheFactory implements FactoryInterface
{
    public function __invoke(Containerinterface $container, $requestedName, array $options = null)
    {

        $config = $container->get('config');

        if (isset($config['seworqs']['cache'])) {
            if (is_string($config['seworqs']['cache'])) {
                $cache = $container->get($config['seworqs']['cache']);
            } elseif (is_array($config['seworqs']['cache'])) {
                $storageFactory = $container->get(StorageAdapterFactoryInterface::class);
                $cache = $storageFactory->createFromArrayConfiguration($config['seworqs']['cache']);
            }

            return $cache;
        }
        return null;
    }
}
