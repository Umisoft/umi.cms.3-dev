<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\api\object;

use umicms\orm\object\CmsObject;
use umicms\project\module\structure\api\collection\InfoBlockCollection;

/**
 * Информационный блок сайта.
 *
 * @property string $infoblockName название информационного блока
 */
abstract class BaseInfoBlock extends CmsObject
{
    /**
     * Имя поля для хранения названия информационного блока
     */
    const FIELD_INFOBLOCK_NAME = 'infoblockName';

    /**
     * Проверяет валидность названия информационного блока.
     * @return bool
     */
    public function validateInfoblockName()
    {
        $result = true;
        /**
         * @var InfoBlockCollection $collection
         */
        $collection = $this->getCollection();

        if (!$collection->checkInfoblockNameUniqueness($this)) {
            $result = false;
            $this->getProperty(self::FIELD_INFOBLOCK_NAME)->addValidationErrors(
                [$this->translate('Infoblock name is not unique')]
            );
        }

        return $result;
    }
}
 