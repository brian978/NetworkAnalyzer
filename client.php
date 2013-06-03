<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

$output = shell_exec('java -jar proxy/dispatcher.jar -mode client -command "tcpdump -i eth0 -nqt -c 20"');
$output = explode(chr(13) . chr(10), $output);

var_dump($output);
