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

use umi\i18n\translator\ITranslator;
use umi\toolkit\IToolkit;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\templating\helper\AccessResource;
use umicms\templating\helper\TranslationHelper;

/**
 * Расширение для подключения помощников шаблонов в Smarty-шаблонах.
 */
class TemplatingSmartyExtension implements ISmartyExtension
{
    /**
     * @var string $translateFunctionName имя функции для перевода
     */
    public $translateFunctionName = 'translate';
    /**
     * @var string $isAllowedResourceFunctionName имя функции для проверки прав на ресурс
     */
    public $isAllowedResourceFunctionName = 'isAllowedResource';

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
     * {@important}
     */
    public function getName()
    {
        return __CLASS__;
    }

    /**
     * {@important}
     */
    public function getFunctions()
    {
        return [
            $this->translateFunctionName => [$this, 'getTranslationHelper'],
            $this->isAllowedResourceFunctionName => [$this, 'getIsAllowedResourceHelper']
        ];
    }

    /**
     * Возвращает помощник шаблонов для локализации.
     * @param array $params параметры
     * @return string
     */
    public function getTranslationHelper(array $params)
    {
        static $helper;

        if (!$helper) {
            /** @var CmsDispatcher $dispatcher */
            $dispatcher = $this->toolkit->getService('umi\hmvc\dispatcher\IDispatcher');
            $helper = new TranslationHelper($dispatcher);
            /** @var ITranslator $translator */
            $translator = $this->toolkit->getService('umi\i18n\translator\ITranslator');
            $helper->setTranslator($translator);
        }

        return $helper->translate($params['message']);

    }

    /**
     * Возвращает помощник шаблонов для проверки прав.
     * @param array $params
     * @return AccessResource
     */
    public function getIsAllowedResourceHelper(array $params)
    {
        static $isAllowedResourceHelper;

        if (!$isAllowedResourceHelper) {
            /** @var CmsDispatcher $dispatcher */
            $dispatcher = $this->toolkit->getService('umi\hmvc\dispatcher\IDispatcher');
            $isAllowedResourceHelper = new AccessResource($dispatcher);
        }

        return $isAllowedResourceHelper->isAllowedResource($params['componentPath'], $params['resources']);
    }
}