<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace AAA\Model;

use Users\Model\Users;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\ServiceLocatorInterface;

class Authentication extends AuthenticationService
{
    /**
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $dbAdapter;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        /** @var $dbAdapter \Zend\Db\Adapter\Adapter */
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

        parent::__construct(
            null,
            new DbTable(
                $dbAdapter,
                'users',
                'username',
                'password'
            )
        );

        $this->dbAdapter = $dbAdapter;
    }

    /**
     * @param array $credentials
     *
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setCredentials(array $credentials)
    {
        $defaults = array(
            'username' => 'dummy',
            'password' => ''
        );

        $credentials = array_merge($defaults, $credentials);

        $this->adapter
            ->setIdentity($credentials['username'])
            ->setCredential($this->createPassword($credentials));

        return $this;
    }

    /**
     * Encodes and encrypts the password
     *
     * @param array $credentials
     *
     * @return string
     */
    protected function createPassword($credentials)
    {
        /** @var $platform \Zend\Db\Adapter\Platform\PlatformInterface */
        $platform = $this->dbAdapter->getPlatform();
        $password = '';

        /** @var $select \Zend\Db\Sql\Select */
        $select = $this->adapter->getDbSelect();
        $select->from('users')
            ->where(
                'username = ' . $platform->quoteValue($credentials['username'])
            );

        try {
            /** @var $result \Zend\Db\ResultSet\ResultSet */
            $result = $this->dbAdapter->query(
                $select->getSqlString(),
                Adapter::QUERY_MODE_EXECUTE
            );
        } catch (\Exception $e) {
        }

        if (isset($result)) {
            if ($result->count() === 1) {
                $row      = $result->current();
                $data     = Users::processPasswordHash($row['password']);
                $password = Users::generatePasswordHash(
                    $credentials['password'],
                    $data
                );
            }
        }

        return $password;
    }
}