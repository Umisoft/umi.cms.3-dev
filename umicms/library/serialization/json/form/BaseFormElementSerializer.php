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
use umi\form\element\IChoiceFormElement;
use umi\i18n\translator\ITranslator;
use umi\validation\IValidationAware;
use umi\validation\TValidationAware;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для элемента формы.
 */
class BaseFormElementSerializer extends BaseSerializer implements IValidationAware
{
    use TValidationAware;

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

        $dictionaries = isset($options['dictionaries']) ? $options['dictionaries'] : [];
        if ($label = $element->getLabel()) {
            $result['label'] = $this->translator->translate($dictionaries, $label);
        }

        if ($attributes = $element->getAttributes()) {
            $result['attributes'] = $attributes;
        }

        if ($dataSource = $element->getDataSource()) {
            $result['dataSource'] = $dataSource;
        }

        if ($value = $element->getValue()) {
            $result['value'] = $value;
        }

        if ($validatorsConfig = $element->getValidatorsConfig()) {

            $result['validators'] = [];
            foreach ($validatorsConfig as $validatorType => $validatorOptions) {
                $validator = $this->createValidator($validatorType, $validatorOptions);
                $result['validators'][] = [
                    'type' => $validatorType,
                    'message' => $this->translator->translate($dictionaries, $validator->getErrorLabel()),
                    'options' => $validatorOptions
                ];
            }
        }

        if ($filtersConfig = $element->getFiltersConfig()) {
            $result['filters'] = [];
            foreach ($filtersConfig as $filterType => $filterOptions) {
                $result['filters'][] = [
                    'type' => $filterType,
                    'options' => $filterOptions
                ];
            }
        }

        if ($element instanceof IChoiceFormElement) {
            if ($choices = $element->getStaticChoices()) {
                $result['choices'] = [];
                foreach ($choices as $value => $label) {
                    $result['choices'][] = [
                        'value' => $value,
                        'label' => $this->translator->translate($dictionaries, $label)
                    ];
                }
            }
        }

        $this->delegate($result, $options);
    }
}
 