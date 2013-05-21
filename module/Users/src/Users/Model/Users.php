<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Users\Model;

use Library\Entity\AbstractEntity;
use Library\Model\AbstractDbModel;

class Users extends AbstractDbModel
{
    protected $table = 'users';

    public function fetch($locale, $params = array())
    {
        $this->addJoin(
            'user_roles',
            'user_roles.id = users.role_id',
            array('roleName' => 'name_' . $locale)
        );

        return parent::fetch();
    }

    /**
     * Processes the password has so it returns the salt from it
     *
     * @param string $hash
     *
     * @return array
     */
    public static function processPasswordHash($hash)
    {
        $salt    = '';
        $hashCut = 0;

        if (is_string($salt) && strlen($hash) > 3) {
            $hashCutLen = substr($hash, -1, 1);
            $hashCut    = substr($hash, -$hashCutLen - 1, $hashCutLen);
            $saltLen    = substr($hash, -$hashCutLen - 3, 2);
            $salt       = substr($hash, $hashCut, $saltLen);
        }

        return array(
            'salt' => $salt,
            'hashCut' => $hashCut
        );
    }

    /**
     * Hashes the password with a new salt or an existing one
     *
     * @param string $password
     * @param array $data
     *
     * @return string
     */
    public static function generatePasswordHash($password, $data = array())
    {
        if (empty($data)) {
            $salt    = substr(sha1(uniqid('', true) . time()), 0, rand(10, 20));
            $saltLen = strlen($salt);
            $hashCut = rand(4, $saltLen);
        } else {
            $salt    = $data['salt'];
            $saltLen = strlen($salt);
            $hashCut = $data['hashCut'];
        }

        $hashCutLen = strlen((string)$hashCut);
        $hash       = hash('sha512', $password . $salt);
        $hash       = substr($hash, 0, $hashCut) . $salt . substr(
            $hash,
            $hashCut
        ) . $saltLen . $hashCut . $hashCutLen;

        return $hash;
    }

    /**
     * @param \Users\Entity\User $object
     *
     * @return int
     */
    protected function doInsert($object)
    {
        $result = 0;

        $data            = array();
        $data['name']    = $object->getName();
        $data['email']   = $object->getEmail();
        $data['role_id'] = $object->getRole()->getId();

        try {
            // If successful will return the number of rows
            $result = $this->insert($data);
        } catch (\Exception $e) {
        }

        return $result;
    }

    /**
     * @param \Users\Entity\User $object
     *
     * @return int
     */
    protected function doUpdate($object)
    {
        $data            = array();
        $data['name']    = $object->getName();
        $data['email']   = $object->getEmail();
        $data['role_id'] = $object->getRole()->getId();

        return $this->executeUpdateById($data, $object);
    }
}