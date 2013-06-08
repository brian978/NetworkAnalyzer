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
        $timeDiff      = isset($args[1]) ? $args[1] : 1;
        $bandwidthType = 0;

        // To avoid division by 0
        if ($timeDiff == 0) {
            $timeDiff = 1;
        }

        $bandwidth = $octetsDiff / $timeDiff;

        // Converting from B/s to the highest unit available up to TB/s speeds
        while (floor($bandwidth) > 1000 && $bandwidthType < 4) {
            $bandwidth /= 1000;
            $bandwidthType++;
        }

        return array(
            'bandwidth' => round($bandwidth, 2),
            'type' => $bandwidthType,
        );
    }
}