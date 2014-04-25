<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\templating\engine\php;

use umi\i18n\translator\ITranslator;
use umi\templating\engine\php\IPhpExtension;
use umicms\hmvc\dispatcher\CmsDispatcher;
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
            $this->translateFunctionName => [$this->getTranslationHelper(), 'translate']
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
}
 