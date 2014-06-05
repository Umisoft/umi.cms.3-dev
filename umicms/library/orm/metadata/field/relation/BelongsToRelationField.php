<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
 