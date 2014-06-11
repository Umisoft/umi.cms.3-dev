<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\templating\engine\php;

use umi\i18n\translator\ITranslator;
use umi\templating\engine\php\IPhpExtension;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\templating\helper\AccessResource;
use umicms\templating\helper\TranslationHelper;

/**
 * Расширение для подключения помощников шаблонов в PHP-шаблонах.
 */
class TemplatingPhpExtension implements IPhpExtension
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
            $this->translateFunctionName => [$this->getTranslationHelper(), 'translate'],
            $this->isAllowedResourceFunctionName => [$this->getIsAllowedResourceHelper(), 'isAllowedResource']
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
 