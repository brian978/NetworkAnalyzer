<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

var_dump(shell_exec('java -jar NetworkAnalyzerClient.jar "tcpdump -i eth0 -nqt -c 50"'));die();
