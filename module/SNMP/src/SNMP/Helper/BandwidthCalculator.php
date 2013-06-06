<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Helper;

class BandwidthCalculator implements HelperInterface
{
    public function __invoke()
    {
        $args          = func_get_args();
        $octetsDiff    = $args[0];
        $timeDiff      = $args[1];
        $bandwidthType = 0;

        // To avoid division by 0
        if ($timeDiff == 0) {
            $timeDiff = 1;
        }

        // The interfaces should only support TB speeds
        while (floor($octetsDiff) > 1024 && $bandwidthType < 4) {
            $octetsDiff /= 1024;
            $bandwidthType++;
        }

        $bandwidth = ($octetsDiff) / ($timeDiff);

        return array(
            'bandwidth' => round($bandwidth, 2),
            'type' => $bandwidthType,
        );
    }
}