<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\json\orm;

use umi\i18n\translator\ITranslator;
use umi\orm\metadata\field\BaseField;
use umi\orm\metadata\field\IRelationField;
use umi\orm\metadata\field\relation\HasManyRelationField;
use umi\orm\metadata\field\relation\ManyToManyRelationField;
use umi\validation\IValidationAware;
use umi\validation\TValidationAware;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для поля объекта.
 */
class FieldSerializer extends BaseSerializer implements IValidationAware
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
     * Сериализует поле в JSON.
     * @param BaseField $field
     * @param array $options опции сериализации
     */
    public function __invoke(BaseField $field, array $options = [])
    {
        $dictionaries = isset($options['dictionaries']) ? $options['dictionaries'] : [];

        $info = [
            'name' => $field->getName(),
            'displayName' => $this->translator->translate($dictionaries, $field->getName()),
            'type' => $field->getType(),
            'dataType' => $field->getDataType(),
            'readOnly' => $field->getIsReadOnly(),
            'default' => $field->getDefaultValue()
        ];

        if ($validatorsConfig = $field->getValidatorsConfig()) {

            $info['validators'] = [];
            foreach ($validatorsConfig as $validatorType => $validatorOptions) {
                $validator = $this->createValidator($validatorType, $validatorOptions);
                $info['validators'][] = [
                    'type' => $validatorType,
                    'message' => $this->translator->translate($dictionaries, $validator->getErrorLabel()),
                    'options' => $validatorOptions
                ];
            }
        }

        if ($filtersConfig = $field->getFiltersConfig()) {
            $info['filters'] = [];
            foreach ($filtersConfig as $filterType => $filterOptions) {
                $info['filters'][] = [
                    'type' => $filterType,
                    'options' => $filterOptions
                ];
            }
        }

        if ($field instanceof IRelationField) {
            $info['targetCollection'] = $field->getTargetCollectionName();
        }

        if ($field instanceof HasManyRelationField) {
            $info['targetField'] = $field->getTargetFieldName();
        }

        if ($field instanceof ManyToManyRelationField) {
            $info['targetFieldName'] = $field->getTargetFieldName();
            $info['bridgeCollection'] = $field->getBridgeCollectionName();
            $info['relatedField'] = $field->getRelatedFieldName();
        }

        $this->delegate($info, $options);
    }
}
 