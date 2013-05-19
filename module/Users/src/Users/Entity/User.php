<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Users\Entity;

use Library\Entity\AbstractEntity;

class User extends AbstractEntity
{
    protected $name;
    protected $email;

    /**
     * @var \Users\Entity\Role
     */
    protected $role;

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Users\Entity\Role $role
     */
    public function setRole(Role $role)
    {
        $this->role = $role;
    }

    /**
     * @return \Users\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }
}