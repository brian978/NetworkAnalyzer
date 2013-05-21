<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Permissions\Services;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class InitializeAcl implements FactoryInterface
{
    /**
     * @var array
     */
    protected $permissions;

    /**
     * @var \Zend\Permissions\Acl\Acl
     */
    protected $acl;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config            = $serviceLocator->get('Config');
        $this->permissions = $config['permissions'];
        $this->acl         = new Acl();

        // Now that we have the ACL object we initialize the resources and roles
        $this->createResources();
        $this->createRoles();

        return $this->acl;
    }

    protected function createResources()
    {
        if (!isset($this->permissions['resources'])) {
            throw new \RuntimeException('The permissions array must contain a resources entry');
        }

        foreach ($this->permissions['resources'] as $resource) {
            if (is_array($resource)) {
                $this->acl->addResource(new GenericResource($resource['name']), $resource['parent']);
            } else {
                $this->acl->addResource(new GenericResource($resource));
            }
        }
    }

    protected function createRoles()
    {
        if (!isset($this->permissions['roles'])) {
            throw new \RuntimeException('The permissions array must contain a roles entry');
        }

        foreach ($this->permissions['roles'] as $roleId => $specs) {
            $role = new GenericRole($roleId);

            $this->acl->addRole($role, $specs['inherits']);

            // Setting the allow/deny rules for each role
            foreach ($specs['resources'] as $resourceId => $permissions) {
                // For the resource ID called all we actually mean NULL
                if ($resourceId == 'all') {
                    $resourceId = null;
                }

                // $type is allow/deny
                foreach ($permissions as $type => $privileges) {
                    $this->acl->$type($role, $resourceId, $privileges);
                }
            }
        }
    }
}