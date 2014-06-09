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
use umi\hmvc\view\helper\IsAllowedHelper;
use umi\toolkit\IToolkit;
use umicms\hmvc\dispatcher\CmsDispatcher;

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
     * @var string $isAllowedFunctionName имя функции для проверки прав
     */
    public $isAllowedFunctionName = 'isAllowed';

    /**
     * @var IToolkit $toolkit набор инструментов
     */
    protected $toolkit;

    /**
     * @var IsAllowedHelper $isAllowedHelper
     */
    private $isAllowedHelper;

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
                $this->isAllowedFunctionName,
                $this->getIsAllowedHelper()
            )
        ];
    }

    /**
     * Возвращает помощник вида для проверки прав.
     * @return callable
     */
    protected function getIsAllowedHelper()
    {
        if (!$this->isAllowedHelper) {
            /** @var CmsDispatcher $dispatcher */
            $dispatcher = $this->toolkit->getService('umi\hmvc\dispatcher\IDispatcher');
            $this->isAllowedHelper = new IsAllowedHelper($dispatcher);
        }
        return $this->isAllowedHelper;
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
}
 