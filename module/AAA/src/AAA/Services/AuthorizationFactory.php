<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace AAA\Services;

use AAA\Model\Authentication;
use AAA\Model\Authorization;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthorizationFactory implements FactoryInterface
{
    protected $modelService = 'Users\Model\UsersModel';

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $usersModel = $serviceLocator->get($this->modelService);

        /** @var $auth Authentication */
        $auth = $serviceLocator->get('AAA\Authentication');

        return new Authorization($usersModel, $auth);
    }
}
