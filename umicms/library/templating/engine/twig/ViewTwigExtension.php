<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\templating\engine\twig;

use Twig_Extension;
use Twig_SimpleFunction;
use umi\toolkit\IToolkit;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\hmvc\url\IUrlManager;
use umicms\purifier\IPurifier;
use umicms\templating\helper\AnchorHelper;

/**
 * Расширение Twig для подключения помощников вида.
 */
class ViewTwigExtension extends Twig_Extension
{
    /**
     * @var string $widgetFunctionName имя функции для вызова виджета
     */
    public $widgetFunctionName = 'widget';
    /**
     * @var string $purifyHtml имя функции для очистки контента от XSS
     */
    public $purifyHtmlFunctionName = 'purifyHtml';
    /**
     * @var string $anchorFunctionName имя функции для формирования якорной ссылки
     */
    public $anchorFunctionName = 'anchor';

    /**
     * @var IToolkit $toolkit набор инструментов
     */
    protected $toolkit;

    /**
     * Конструктор.
     * @param IToolkit $toolkit
     */
    public function __construct(IToolkit $toolkit)
    {
        $this->toolkit = $toolkit;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return __CLASS__;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(
                $this->widgetFunctionName,
                $this->getWidgetHelper(),
                ['is_safe' => ['html']]
            ),
            new Twig_SimpleFunction(
                $this->purifyHtmlFunctionName,
                $this->getPurifyHtml()
            ),
            new Twig_SimpleFunction(
                $this->anchorFunctionName,
                [$this->getAnchorHelper(), 'buildAnchorLink']
            )
        ];
    }

    /**
     * Возвращает помощник вида для вызова виджетов
     * @return callable
     */
    protected function getWidgetHelper()
    {
        return function($widgetPath, array $args = []) {
            /** @var CmsDispatcher $dispatcher */
            $dispatcher = $this->toolkit->getService('umi\hmvc\dispatcher\IDispatcher');

            return $dispatcher->executeWidgetByPath($widgetPath, $args);
        };
    }

    /**
     * Хелпер очищающий контент от XSS.
     * @return callable
     */
    protected function getPurifyHtml()
    {
        return function($string, array $options = []) {
            /** @var IPurifier $purifierHtml */
            $purifierHtml = $this->toolkit->getService('umicms\purifier\IPurifier');

            return $purifierHtml->purify($string, $options);
        };
    }

    /**
     * Возвращает помощник для формирования якорных ссылок.
     * @return AnchorHelper
     */
    protected function getAnchorHelper()
    {
        /** @var IUrlManager $urlManager */
        $urlManager = $this->toolkit->getService('umicms\hmvc\url\IUrlManager');

        return new AnchorHelper($urlManager);
    }
}
 