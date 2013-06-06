<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

$output  = '';
$command = 'java -jar ' . getcwd() . '/proxy/dispatcher.jar -mode client -command "tcpdump -i eth1 -nqt -c 20"';

$output = shell_exec($command);
$output = explode(chr(13) . chr(10), $output);

array_pop($output);
var_dump($output);


