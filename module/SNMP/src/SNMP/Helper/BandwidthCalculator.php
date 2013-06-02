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
        $args       = func_get_args();
        $octetsDiff = $args[0];
        $timeDiff   = $args[1];

        // To avoid division by 0
        if ($timeDiff == 0) {
            $timeDiff = 1;
        }

        $bandwidth     = ($octetsDiff * 8) / ($timeDiff);
        $bandwidthType = 0;

        // The interfaces should only support TB speeds
        while (floor($bandwidth) > 1024 && $bandwidthType < 4) {
            $bandwidth = $bandwidth / 1024;
            $bandwidthType++;
        }

        return array(
            'bandwidth' => round($bandwidth, 2),
            'type' => $bandwidthType,
        );
    }
}