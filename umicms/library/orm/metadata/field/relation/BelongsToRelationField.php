<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\metadata\field\relation;

use umi\orm\exception\RuntimeException;
use umi\orm\metadata\field\relation\BelongsToRelationField as FrameworkBelongsToRelationField;
use umi\orm\object\IObject;

/**
 * {@inheritdoc}
 */
class BelongsToRelationField extends FrameworkBelongsToRelationField
{
    /**
     * {@inheritdoc}
     */
    public function preparePropertyValue(IObject $object, $internalDbValue)
    {
        if (is_null($internalDbValue)) {
            return null;
        }

        try {
            return $this->getTargetCollection()->getById($internalDbValue);
        } catch (RuntimeException $e) { }

        return null;

    }
}
 