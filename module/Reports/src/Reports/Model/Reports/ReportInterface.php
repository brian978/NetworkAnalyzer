<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Reports\Model\Reports;

/**
 * Class ReportInterface
 *
 * @package Reports\Model\Reports
 */
interface ReportInterface
{
    public function setData(array $data);

    public function getReport();
}