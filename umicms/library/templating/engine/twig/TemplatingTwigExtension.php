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
use umicms\hmvc\dispatcher\CmsDispatcher;
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
     * @var CmsDispatcher $dispatcher диспетчер
     */
    protected $dispatcher;

    /**
     * Конструктор.
     * @param CmsDispatcher $dispatcher диспетчер
     */
    public function __construct(CmsDispatcher $dispatcher) {
        $this->dispatcher = $dispatcher;
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
        }

        return $helper;

    }
}
 