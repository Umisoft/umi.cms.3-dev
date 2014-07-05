<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\json;

use umicms\exception\InvalidObjectsException;

/**
 * JSON-сериализатор для исключений, связанных с наличием объектов непрошедших валидацию.
 */
class InvalidObjectsExceptionSerializer extends BaseSerializer
{
    /**
     * Сериализует исключение в JSON.
     * @param InvalidObjectsException $exception
     * @param array $options опции сериализации
     */
    public function __invoke(InvalidObjectsException $exception, array $options = [])
    {
        $info = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode()
        ];

        $objects = [];
        foreach ($exception->getInvalidObjects() as $object) {
            $objectsInfo = [
                'collection' => $object->getCollectionName(),
                'guid' => $object->guid,
                'displayName' => $object->displayName,
                'invalidProperties' => []
            ];

            foreach ($object->getValidationErrors() as $propertyName => $errors) {
                $objectsInfo['invalidProperties'][] = [
                    'propertyName' => $propertyName,
                    'errors' => $errors
                ];
            }
            $objects[] = $objectsInfo;
        }

        $info['invalidObjects'] = $objects;

        $this->delegate($info, $options);
    }
}