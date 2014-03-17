<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\json\form;

use umi\form\element\BaseFormElement;
use umi\i18n\translator\ITranslator;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для элемента формы.
 */
class BaseFormElementSerializer extends BaseSerializer
{
    /**
     * @var ITranslator $translator транслятор для перевода лейблов элементов
     */
    protected $translator;
    /**
     * Конструктор.
     * @param ITranslator $translator
     */
    public function __construct(ITranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Сериализует элемент формы в JSON.
     * @param BaseFormElement $element
     * @param array $options опции сериализации
     */
    public function __invoke(BaseFormElement $element, array $options = [])
    {
        $result = [
            'type' => $element::TYPE_NAME,
            'name' => $element->getName()
        ];

        if ($label = $element->getLabel()) {
            $labelDictionaries = isset($options['dictionaries']) ? $options['dictionaries'] : [];
            $result['label'] = $this->translator->translate($labelDictionaries, $label);
        }

        if ($attributes = $element->getAttributes()) {
            $result['attributes'] = $attributes;
        }

        if ($dataSource = $element->getDataSource()) {
            $result['dataSource'] = $dataSource;
        }

        $this->delegate($result);
    }
}
 