<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Permission;

use Interop\Container\ContainerInterface;
use Laminas\Cache\Storage\Adapter\Filesystem;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AclFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        if (! $config || ! $config['seworqs'] || ! $config['seworqs']['acl']) {
            $config['seworqs']['acl'] = [];
        }

        $useCache = (isset($config['seworqs']['acl']['cache']) && $config['seworqs']['acl']['cache']);

        $cache = $container->get('SeworqsCache');

        if ($useCache && $cache) {
            if ($cache->hasItem('acl')) {
                $acl = unserialize($cache->getItem('acl'));
            } else {
                $acl = new Acl($config['seworqs']['acl']);
                $aclSerialized = serialize($acl);
                $cache->addItem('acl', $aclSerialized);
            }
            return $acl;
        }

        return new Acl($config['seworqs']['acl']);

        //        if ($cache) {
        //            if ($cache->hasItem('acl')) {
        //                $acl = unserialize($cache->getItem('acl'));
        //            } else {
        //                $acl = new Acl($config['seworqs']['acl']);
        //                $aclSerialized = serialize($acl);
        //                $cache->addItem('acl', $aclSerialized);
        //            }
        //
        //            return $acl;
        //        }
        //
        //        $acl = new Acl($config['seworqs']['acl']);
        //
        //        return $acl;
    }
}
