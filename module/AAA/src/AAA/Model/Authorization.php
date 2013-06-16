<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace AAA\Model;

use Library\Model\AbstractDbModel;

class Authorization
{
    /**
     * @var \Users\Model\Users
     */
    protected $model;

    /**
     * @var Authentication
     */
    protected $auth;

    public function __construct(AbstractDbModel $model, Authentication $auth)
    {
        $this->model = $model;
        $this->auth  = $auth;
    }

    public function getRole()
    {
        $result = current($this->model->getRole($this->auth->getIdentity()));

        return $result['roleName'];
    }
}
