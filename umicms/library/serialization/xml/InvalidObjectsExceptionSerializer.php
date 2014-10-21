<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\xml;

use umicms\exception\InvalidObjectsException;
use umicms\project\Environment;

/**
 * XML-сериализатор для исключений, связанных с наличием объектов непрошедших валидацию.
 */
class InvalidObjectsExceptionSerializer extends BaseSerializer
{
    /**
     * Сериализует исключение в XML.
     * @param InvalidObjectsException $exception
     * @param array $options опции сериализации
     */
    public function __invoke(InvalidObjectsException $exception, array $options = [])
    {
        $this->writeAttribute('message', $exception->getMessage());
        $this->writeAttribute('code', $exception->getCode());

        if (Environment::$showExceptionTrace) {
            $this->getXmlWriter()->startElement('trace');
            $this->delegate($exception->getTraceAsString(), $options);
            $this->getXmlWriter()->endElement();
        }

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

        $this->getXmlWriter()->startElement('invalidObjects');
        $this->delegate($objects, $options);
        $this->getXmlWriter()->endElement();
    }
}