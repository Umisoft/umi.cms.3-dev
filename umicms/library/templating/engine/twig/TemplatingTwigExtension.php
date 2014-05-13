<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\templating\engine\twig;

use Twig_Extension;
use Twig_SimpleFunction;
use umi\i18n\translator\ITranslator;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\templating\helper\FormHelper;
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
     * @var string $formFunctionName имя функции для вывода форм
     */
    public $formFunctionName = 'form';

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
                $this->formFunctionName,
                [$this->getFormHelper(), 'buildForm']
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

    /**
     * Возвращает помощник шаблонов для форм.
     * @return callable
     */
    protected function getFormHelper()
    {
        return function () {
            static $helper;

            if (!$helper) {
                $helper = new FormHelper();
                $helper->setTranslator($this->translator);
            }

            return $helper;
        };
    }
}
 