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
use umi\i18n\translator\ITranslator;
use umi\toolkit\IToolkit;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\templating\helper\AccessResource;
use umicms\templating\helper\TranslationHelper;

/**
 * Расширение Twig для подключения помощников шаблонов.
 */
class TemplatingTwigExtension extends Twig_Extension
{
    /**
     * @var string $translateFunctionName имя функции для перевода
     */
    public $translateFunctionName = 'translate';
    /**
     * @var string string $isAllowedResourceFunctionName имя функции для проверки прав на ресурс
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
                $this->translateFunctionName,
                [$this->getTranslationHelper(), 'translate']
            ),
            new Twig_SimpleFunction(
                $this->isAllowedResourceFunctionName,
                [$this->getIsAllowedResourceHelper(), 'isAllowedResource']
            )
        ];
    }

    /**
     * Возвращает помощник шаблонов для локализации.
     * @return TranslationHelper
     */
    protected function getTranslationHelper()
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

        return $helper;
    }

    /**
     * Возвращает помощник шаблонов для проверки прав.
     * @return AccessResource
     */
    protected function getIsAllowedResourceHelper()
    {
        static $isAllowedResourceHelper;
        if (!$isAllowedResourceHelper) {
            /** @var CmsDispatcher $dispatcher */
            $dispatcher = $this->toolkit->getService('umi\hmvc\dispatcher\IDispatcher');

            $isAllowedResourceHelper = new AccessResource($dispatcher);
        }

        return $isAllowedResourceHelper;
    }
}
 