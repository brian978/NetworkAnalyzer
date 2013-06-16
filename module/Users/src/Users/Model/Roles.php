<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Users\Model;

use Library\Model\AbstractDbModel;

class Roles extends AbstractDbModel
{
    protected $table = 'user_roles';

    protected function doInsert($object)
    {
    }

    protected function doUpdate($object)
    {
    }

    public function doDelete($object)
    {
    }
}
