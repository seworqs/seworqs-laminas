<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Permission;

use Laminas\Permissions\Acl\Acl as LaminasAcl;

class Acl extends LaminasAcl
{
    public const ROLE_GUEST = 'guest';
    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_BACKOFFICE = 'backoffice';
    public const ROLE_ADMINISTRATOR = 'administrator';

    public function __construct(array $config)
    {

        foreach ($config['roles'] as $key => $value) {
            if (is_array($value)) {
                if (! $this->hasRole($value)) {
                    $this->addRole($key, $value);
                }
            } else {
                if (! $this->hasRole($value)) {
                    $this->addRole($value);
                }
            }
        }

        foreach ($config['resources'] as $key => $value) {
            if (is_array($value)) {
                if (! $this->hasResource($value)) {
                    $this->addResource($key, $value);
                }
            } else {
                if (! $this->hasResource($value)) {
                    $this->addResource($value);
                }
            }
        }

        foreach ($config['permissions'] as $key => $value) {
            if ($key === 'allow') {
                foreach ($value as $allow) {
                    $this->allow($allow[0], $allow[1], $allow[2]);
                }
            } elseif ($key === 'deny') {
                foreach ($value as $deny) {
                    $this->deny($deny[0], $deny[1], $deny[3]);
                }
            }
        }
    }
}
