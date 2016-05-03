<?php

namespace Core\Acl;

use Core\Service\Service;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class Builder extends Service
{
    
    public function build()
    {
        $acl = new Acl();
        $config = require_once BASE_DIR . '/config/autoload/permissions.config.php';
        $roles = $config['roles'];
        $permissions = $config['permissions'];
        $resources = $config['resources'];
        
        foreach ($roles as $role) {
            $acl->addRole(new Role($role));
        }
        
        foreach ($resources as $r) {
            $acl->addResource(new Resource($r));
        }
        
        foreach ($permissions as $role => $permission) {
            foreach ($permission as $p)
                $acl->allow($role, $p);
        }
        
        return $acl;
    }
}