<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Library\View\Helpers;

use Zend\View\Helper\AbstractHelper;

class MediaSource extends AbstractHelper
{
    public function __invoke($media_type, $media_name)
    {
        $serverUrl = $this->getView()->getHelperPluginManager()->get('ServerUrl');

        $url = str_replace('index.php', '', $serverUrl() . $_SERVER['PHP_SELF']);

        switch($media_type)
        {
            case 'images':
                $url .= 'images/';
                break;

            default:
                break;
        }

        $url .= $media_name;

        echo $url;
    }
}