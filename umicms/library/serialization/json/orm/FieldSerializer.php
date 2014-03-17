<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\json\orm;

use umi\i18n\translator\ITranslator;
use umi\orm\metadata\field\BaseField;
use umi\orm\metadata\field\IRelationField;
use umi\orm\metadata\field\relation\HasManyRelationField;
use umi\orm\metadata\field\relation\ManyToManyRelationField;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для поля объекта.
 */
class FieldSerializer extends BaseSerializer
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
            'readOnly' => $field->getIsReadOnly(),
            'default' => $field->getDefaultValue()
        ];

        if ($validatorsConfig = $field->getValidatorsConfig()) {
            $info['validators'] = [];
            foreach ($validatorsConfig as $validatorType => $validatorOptions) {
                $info['validators'][] = [
                    'type' => $validatorType,
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

            $targetCollection = $field->getTargetCollection();
            $info['mirrorField'] = $targetCollection->getMetadata()->getFieldByRelation($field->getTargetFieldName(), $field->getBridgeCollectionName())->getName();
        }

        $this->delegate(
            $info
        );
    }
}
 