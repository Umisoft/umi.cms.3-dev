<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\json\form;

use umi\form\fieldset\FieldSet;
use umi\i18n\translator\ITranslator;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для набора сущностей формы.
 */
class FieldSetSerializer extends BaseSerializer
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
     * Сериализует набор сущностей формы в JSON.
     * @param FieldSet $fieldSet
     * @param array $options опции сериализации
     */
    public function __invoke(FieldSet $fieldSet, array $options = [])
    {

        if (!isset($options['dictionaries'])) {
            $options['dictionaries'] = $this->getLabelDictionaries($fieldSet);
        }

        $result = [
            'type' => $fieldSet::TYPE_NAME
        ];

        if ($label = $fieldSet->getLabel()) {
            $result['label'] = $this->translator->translate($options['dictionaries'], $label);
        }

        if ($name = $fieldSet->getName()) {
            $result['name'] = $name;
        }

        if ($attributes = $fieldSet->getAttributes()) {
            $result['attributes'] = $attributes;
        }

        if ($elements = iterator_to_array($fieldSet, false)) {
            $result['elements'] = $elements;
        }

        $this->delegate($result, $options);
    }

    /**
     * Возвращает словари для перевода лейблов сущностей
     * @param FieldSet $fieldSet
     * @return array
     */
    protected function getLabelDictionaries(FieldSet $fieldSet)
    {
        return [];
    }
}
 