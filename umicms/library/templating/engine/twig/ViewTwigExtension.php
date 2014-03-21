<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\templating\engine\twig;

use umi\extension\twig\ViewTwigExtension as FrameworkViewTwigExtension;
use umicms\hmvc\dispatcher\Dispatcher;

/**
 * {@inheritdoc}
 */
class ViewTwigExtension extends FrameworkViewTwigExtension
{
    /**
     * @var Dispatcher $dispatcher диспетчер
     */
    protected $dispatcher;

    /**
     * {@inheritdoc}
     */
    protected function getWidgetHelper()
    {
        return function($widgetPath, array $args = []) {
            return $this->dispatcher->executeWidgetByPath($widgetPath, $args);
        };
    }
}
 