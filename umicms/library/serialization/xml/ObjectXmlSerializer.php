<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\serialization\xml;

use umicms\serialization\exception\UnexpectedValueException;

/**
 * XML-сериализатор для произвольных объектов "по умолчанию".
 */
class ObjectXmlSerializer extends BaseXmlSerializer
{
    /**
     * {@inheritdoc}
     */
    public function __invoke($object)
    {
        if (!is_object($object)) {
            throw new UnexpectedValueException('Cannot serialize object. Object instance required.');
        }

        $publicVars = get_object_vars($object);
        foreach ($publicVars as $propName => $value) {
            var_dump($propName);

        }
        var_dump(get_class($object));
        exit;
    }
}
