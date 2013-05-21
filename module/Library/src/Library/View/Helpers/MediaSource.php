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
    /**
     * @param string $mediaType
     * @param string $mediaName
     */
    public function __invoke($mediaType, $mediaName)
    {
        /** @var $view \Zend\View\Renderer\PhpRenderer */
        $view = $this->getView();

        /** @var $basePath \Zend\View\Helper\BasePath */
        $basePath = $view->getHelperPluginManager()->get('BasePath');
        $url      = $basePath() . '/';

        switch ($mediaType) {
            case 'images':
                $url .= 'images/';
                break;
        }

        $url .= $mediaName;

        echo $url;
    }
}
