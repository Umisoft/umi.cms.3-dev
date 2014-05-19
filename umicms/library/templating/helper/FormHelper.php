<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\templating\helper;

use umi\form\element\Checkbox;
use umi\form\element\CheckboxGroup;
use umi\form\element\IFormButton;
use umi\form\element\IFormInput;
use umi\form\element\MultiSelect;
use umi\form\element\Select;
use umi\form\element\Textarea;
use umi\form\fieldset\FieldSet;
use umi\form\fieldset\IFieldSet;
use umi\form\IForm;
use umi\form\IFormEntity;
use umi\i18n\ILocalizable;
use umi\i18n\translator\ITranslator;
use umicms\exception\InvalidArgumentException;
use umicms\form\element\Captcha;

/**
 * Помощник шаблонов для вывода форм.
 */
class FormHelper implements ILocalizable
{

    /**
     * @var ITranslator $translator транслятор
     */
    private $translator;

    /**
     * {@inheritdoc}
     */
    public function setTranslator(ITranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Генерирует массив для построения формы.
     * @param IForm $form
     * @return array
     */
    public function buildForm(IForm $form)
    {
        $options = $form->getOptions();
        $dictionaries = isset($options['dictionaries']) ? $options['dictionaries'] : [];

        $result = $this->buildFieldset($form, $dictionaries);
        $result['type'] = 'form';

        return $result;
    }

    /**
     * Генерирует массив для построения набора элементов.
     * @param IFieldSet $fieldSet
     * @param array $dictionaries имена словарей для перевода
     * @return array
     */
    protected function buildFieldset(IFieldSet $fieldSet, array $dictionaries = [])
    {
        $result = [
            'element' => $fieldSet,
            'type' => 'fieldset',
            'label' => $fieldSet->getLabel() ? $this->translator->translate($dictionaries, $fieldSet->getLabel()) : '',
            'attributes' => $this->buildAttributes($fieldSet->getAttributes()),
            'elements' => []
        ];

        foreach ($fieldSet as $element) {
            $result['elements'][] = $this->buildElement($element, $dictionaries);
        }

        return $result;
    }

    /**
     * Генерирует массив для построения элемента формы.
     * @param IFormEntity $element
     * @param array $dictionaries имена словарей для перевода
     * @throws InvalidArgumentException если тип элемента неизвестен
     * @return array
     */
    protected function buildElement(IFormEntity $element, array $dictionaries = [])
    {
        switch(true)
        {
            case $element instanceof FieldSet: {
                return $this->buildFieldset($element, $dictionaries);
            }
            case $element instanceof Select: {
                return $this->buildSelect($element, $dictionaries);
            }
            case $element instanceof CheckboxGroup: {
                return $this->buildCheckboxGroup($element, $dictionaries);
            }
            case $element instanceof Checkbox: {
                return $this->buildCheckbox($element, $dictionaries);
            }
            case $element instanceof IFormButton: {
                return $this->buildButton($element, $dictionaries);
            }
            case $element instanceof IFormInput: {
                return $this->buildInput($element, $dictionaries);
            }
            case $element instanceof Textarea: {
                return $this->buildTextarea($element, $dictionaries);
            }
            case $element instanceof Captcha: {
                return $this->buildCaptcha($element, $dictionaries);
            }
            default:
                throw new InvalidArgumentException(
                    sprintf('Cannot build element "%s". Element type is unknown.', $element->getName())
                );
        }
    }

    /**
     * Генерирует массив для построения выпадающего списка формы.
     * @param Select $select
     * @param array $dictionaries имена словарей для перевода
     * @return array
     */
    protected function buildSelect(Select $select, array $dictionaries = [])
    {
        $attributes = array_merge(
            ['name' => $select->getElementName()],
            $select->getAttributes()
        );

        if ($select instanceof MultiSelect) {
            $attributes['multiple'] = 'multiple';
        }

        $selected = (array) $select->getValue();
        $choices = [];

        foreach ($select->getChoices() as $value => $label) {
            $attr = ['value' => $value];

            if (in_array($value, $selected)) {
                $attr += [
                    'selected' => 'selected'
                ];
            }

            $choices[] = [
                'label' => $label ? $this->translator->translate($dictionaries, $label) : '',
                'attributes' => $this->buildAttributes($attr)
            ];
        }

        return [
            'element' => $select,
            'label' => $select->getLabel() ? $this->translator->translate($dictionaries, $select->getLabel()) : '',
            'type' => 'select',
            'attributes' => $this->buildAttributes($attributes),
            'choices' => $choices
        ];
    }

    /**
     * Генерирует массив для построения группы флажков формы.
     * @param CheckboxGroup $checkboxGroup
     * @param array $dictionaries имена словарей для перевода
     * @return array
     */
    protected function buildCheckboxGroup(CheckboxGroup $checkboxGroup, array $dictionaries = [])
    {
        $attributes = array_merge(
            $checkboxGroup->getAttributes(),
            [
                'name' => $checkboxGroup->getElementName(),
                'type' => $checkboxGroup->getInputType()
            ]
        );

        $selected = (array) $checkboxGroup->getValue();
        $choices = [];

        foreach ($checkboxGroup->getChoices() as $value => $label) {
            $attr = ['value' => $value];

            if (in_array($value, $selected)) {
                $attr += [
                    'checked' => 'checked'
                ];
            }

            $choices[] = [
                'label' => $label ? $this->translator->translate($dictionaries, $label) : '',
                'attributes' => $this->buildAttributes($attributes + $attr)
            ];
        }

        return [
            'element' => $checkboxGroup,
            'label' => $checkboxGroup->getLabel() ? $this->translator->translate($dictionaries, $checkboxGroup->getLabel()) : '',
            'type' => 'checkboxGroup',
            'choices' => $choices
        ];
    }

    /**
     * Генерирует массив для построения текстовой области формы.
     * @param Textarea $textarea
     * @param array $dictionaries имена словарей для перевода
     * @return array
     */
    protected function buildTextarea(Textarea $textarea, array $dictionaries = [])
    {
        $attributes = array_merge(
            $textarea->getAttributes(),
            ['name' => $textarea->getElementName()]
        );

        return [
            'element' => $textarea,
            'label' => $textarea->getLabel() ? $this->translator->translate($dictionaries, $textarea->getLabel()) : '',
            'type' => 'textarea',
            'attributes' => $this->buildAttributes($attributes)
        ];
    }

    /**
     * Генерирует массив для построения поля ввода формы.
     * @param IFormInput $input
     * @param array $dictionaries имена словарей для перевода
     * @return array
     */
    protected function buildInput(IFormInput $input, array $dictionaries = [])
    {
        $attributes = array_merge(
            $input->getAttributes(),
            [
                'type' => $input->getInputType(),
                'name' => $input->getElementName(),
                'value' => $input->getValue()
            ]
        );

        return [
            'element' => $input,
            'label' => $input->getLabel() ? $this->translator->translate($dictionaries, $input->getLabel()) : '',
            'type' => 'input',
            'attributes' => $this->buildAttributes($attributes)
        ];
    }

    /**
     * Генерирует массив для построения флажка формы.
     * @param Checkbox $checkbox
     * @param array $dictionaries имена словарей для перевода
     * @return array
     */
    protected function buildCheckbox(Checkbox $checkbox, array $dictionaries = [])
    {
        $attributes = array_merge(
            $checkbox->getAttributes(),
            [
                'type' => $checkbox->getInputType(),
                'name' => $checkbox->getElementName(),
                'value' => 1
            ]
        );

        if ($checkbox->getValue()) {
            $attributes['checked'] = 'checked';
        }

        return [
            'element' => $checkbox,
            'label' => $checkbox->getLabel() ? $this->translator->translate($dictionaries, $checkbox->getLabel()) : '',
            'type' => 'checkbox',
            'attributes' => $this->buildAttributes($attributes)
        ];
    }

    /**
     * Генерирует массив для построения кнопки формы.
     * @param IFormButton $button
     * @param array $dictionaries имена словарей для перевода
     * @return array
     */
    protected function buildButton(IFormButton $button, array $dictionaries = [])
    {
        $attributes = array_merge(
            $button->getAttributes(),
            [
                'type' => $button->getButtonType(),
                'name' => $button->getElementName(),
                'value' => $button->getValue(),
            ]
        );

        return [
            'element' => $button,
            'label' => $button->getLabel() ? $this->translator->translate($dictionaries, $button->getLabel()) : '',
            'type' => 'button',
            'attributes' => $this->buildAttributes($attributes)
        ];

    }

    /**
     * Генерирует массив для построения captcha.
     * @param Captcha $captchaElement
     * @param array $dictionaries имена словарей для перевода
     * @return array
     */
    protected function buildCaptcha($captchaElement, $dictionaries)
    {
        $attributes = array_merge(
            $captchaElement->getAttributes(),
            [
                'name' => $captchaElement->getElementName(),
                'value' => $captchaElement->getValue()
            ]
        );

        return [
            'element' => $captchaElement,
            'label' => $captchaElement->getLabel() ? $this->translator->translate($dictionaries, $captchaElement->getLabel()) : '',
            'type' => 'captcha',
            'attributes' => $this->buildAttributes($attributes)
        ];
    }

    /**
     * Генерирует строку аттрибутов для элемента.
     * @param array $attributes массив аттрибутов элемента
     * @return string
     */
    protected function buildAttributes(array $attributes)
    {
        $strings = [];

        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                continue;
            }
            $strings[] = $key . '="' . $value . '"';
        }

        return implode(' ', $strings);
    }

}
 