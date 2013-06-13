<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Library\Model;

interface DbModelAwareInterface
{
    /**
     * @param AbstractDbModel $model
     * @return $this
     */
    public function setModel(AbstractDbModel $model);

    /**
     * @return AbstractDbModel
     */
    public function getModel();
}
