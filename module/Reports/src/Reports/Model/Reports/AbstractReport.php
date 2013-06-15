<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Reports\Model\Reports;

use Library\Model\AbstractDbModel;

abstract class AbstractReport implements ReportInterface
{
    /**
     * Sets the memory limit (for now)
     *
     */
    public function __construct()
    {
        ini_set('memory_limit', '-1');
    }

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var \Devices\Model\BandwidthLogs
     */
    protected $model;

    /**
     * @param AbstractDbModel $model
     */
    public function setModel(AbstractDbModel $model)
    {
        $this->model = $model;
    }
}
