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
     * @var string string $isAllowedResourceFunctionName имя функции для проверки прав на виджет
     */
    public $isAllowedResourceFunctionName = 'isAllowedResource';

    /**
     * @var CmsDispatcher $dispatcher диспетчер
     */
    protected $dispatcher;
    /**
     * @var ITranslator $translator
     */
    protected $translator;

    /**
     * Конструктор.
     * @param CmsDispatcher $dispatcher диспетчер
     * @param ITranslator $translator
     */
    public function __construct(CmsDispatcher $dispatcher, ITranslator $translator) {
        $this->dispatcher = $dispatcher;
        $this->translator = $translator;
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
            $helper = new TranslationHelper($this->dispatcher);
            $helper->setTranslator($this->translator);
        }

        return $helper;
    }

    protected function getIsAllowedResourceHelper()
    {
        static $isAllowedResourceHelper;

        if (!$isAllowedResourceHelper) {
            $isAllowedResourceHelper = new AccessResource($this->dispatcher);
        }

        return $isAllowedResourceHelper;
    }
}
 