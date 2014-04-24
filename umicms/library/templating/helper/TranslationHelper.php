<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\templating\helper;

use umi\i18n\ILocalizable;
use umi\i18n\translator\ITranslator;
use umicms\hmvc\component\BaseComponent;
use umicms\hmvc\dispatcher\CmsDispatcher;

/**
 * Помощники шаблонов для локализации.
 */
class TranslationHelper implements ILocalizable
{

    /**
     * @var CmsDispatcher $dispatcher диспетчер
     */
    protected $dispatcher;
    /**
     * @var ITranslator $translator транслятор
     */
    private $translator;

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
    public function setTranslator(ITranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Переводит сообщение.
     * @param string $message сообщение
     * @param array $placeholders список плейсхолдеров
     * @param array $dictionaries словари для перевода
     * @return string
     */
    public function translate($message, array $placeholders = [], array $dictionaries = [])
    {
        if (!$dictionaries) {
            $component = $this->dispatcher->getCurrentContext()->getComponent();
            if ($component instanceof BaseComponent) {
                $dictionaries = $component->getDictionariesNames();
            }
        }

        if ($this->translator) {
            return $this->translator->translate($dictionaries, $message, $placeholders);
        }
        $replace = [];
        foreach ($placeholders as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        return strtr($message, $replace);
    }
}
 