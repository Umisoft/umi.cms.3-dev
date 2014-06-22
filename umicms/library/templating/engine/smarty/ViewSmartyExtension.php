<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\templating\engine\smarty;

use umi\hmvc\view\helper\IsAllowedHelper;
use umi\hmvc\view\IView;
use umi\toolkit\IToolkit;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\purifier\IPurifier;

/**
* Расширение для подключения помощников вида в Smarty-шаблонах.
 */
class ViewSmartyExtension implements ISmartyExtension
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
     * @var string $escapeHtmlFunctionName имя функции для экранирования html
     */
    public $escapeHtmlFunctionName = 'escape';
    /**
     * @var string $escapeJsFunctionName имя функции для экранирования js
     */
    public $escapeJsFunctionName = 'escapeJs';
    /**
     * @var string $escapeCssFunctionName имя функции для экранирования css
     */
    public $escapeCssFunctionName = 'escapeCss';
    /**
     * @var string $escapeUrlFunctionName имя функции для экранирования url
     */
    public $escapeUrlFunctionName = 'escapeUrl';
    /**
     * @var string $purifyHtml имя функции для очистки контента от XSS
     */
    public $purifyHtmlFunctionName = 'purifyHtml';

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
            $this->widgetFunctionName => [$this, 'getWidgetHelper'],
            $this->isAllowedFunctionName => [$this, 'getIsAllowedHelper'],
            $this->escapeHtmlFunctionName => [$this, 'getEscapeHtmlHelper'],
            $this->escapeJsFunctionName => [$this, 'getEscapeJsHelper'],
            $this->escapeCssFunctionName => [$this, 'getEscapeCssHelper'],
            $this->escapeUrlFunctionName => [$this, 'getEscapeUrlHelper'],
            $this->purifyHtmlFunctionName => [$this, 'getPurifyHtml']
        ];
    }

    /**
     * Возвращает помощник вида для проверки прав.
     * @return callable
     */
    public function getIsAllowedHelper()
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
     * @param array $params параметры вызываемого виджета
     * @return string|IView
     */
    public function getWidgetHelper($params)
    {
        if (!isset($params['params'])) {
            $params['params'] = [];
        }
        /** @var CmsDispatcher $dispatcher */
        $dispatcher = $this->toolkit->getService('umi\hmvc\dispatcher\IDispatcher');
        return $dispatcher->executeWidgetByPath($params['widgetPath'], $params['params']);
    }

    /**
     * Хелпер html-экранирования строки.
     * @param array $params параметры
     * @return string
     */
    public function getEscapeHtmlHelper(array $params)
    {
        return htmlspecialchars($params['string'], ENT_QUOTES | ENT_SUBSTITUTE);
    }

    /**
     * Хелпер для экранирования url.
     * @param array $params параметры
     * @return string
     */
    public function getEscapeUrlHelper(array $params)
    {
        return rawurlencode($params['string']);
    }

    /**
     * Хелпер js-экранирования строки.
     * This function is adapted from code coming from Twig.
     * @copyright 2009 Fabien Potencier
     * @param array $params параметры
     * @return mixed
     */
    public function getEscapeJsHelper(array $params)
    {
        // strategy from twig template engine
        return preg_replace_callback('#[^a-zA-Z0-9,\._]#Su', function($matches) {
            $char = $matches[0];

            // \xHH
            if (!isset($char[1])) {
                return '\\x' . strtoupper(substr('00'.bin2hex($char), -2));
            }

            // \uHHHH
            $char = mb_convert_encoding($char, 'UTF-16BE', 'UTF-8');

            return '\\u'.strtoupper(substr('0000'.bin2hex($char), -4));
        }, $params['string']);
    }

    /**
     * Хелпер css-экранирования строки.
     * This function is adapted from code coming from Twig.
     * @copyright 2009 Fabien Potencier
     * @param array $params параметры
     * @return mixed
     */
    public function getEscapeCssHelper(array $params)
    {
        return preg_replace_callback('#[^a-zA-Z0-9]#Su', function($matches) {
            $char = $matches[0];

            // \xHH
            if (!isset($char[1])) {
                $hex = ltrim(strtoupper(bin2hex($char)), '0');
                if (0 === strlen($hex)) {
                    $hex = '0';
                }

                return '\\'.$hex.' ';
            }

            // \uHHHH
            $char = mb_convert_encoding($char, 'UTF-16BE', 'UTF-8');

            return '\\'.ltrim(strtoupper(bin2hex($char)), '0').' ';
        }, $params['string']);
    }

    /**
     * Хелпер очищающий контент от XSS.
     * @param array $params параметры
     * @return string
     */
    public function getPurifyHtml(array $params)
    {
        if (!isset($params['options'])) {
            $params['options'] = [];
        }
        /** @var IPurifier $purifierHtml */
        $purifierHtml = $this->toolkit->getService('umicms\purifier\IPurifier');
        return $purifierHtml->purify($params['string'], $params['options']);
    }
}
 