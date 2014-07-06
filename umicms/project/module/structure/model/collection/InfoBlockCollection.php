<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\CmsCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\structure\model\object\BaseInfoBlock;

/**
 * Коллекция для работы с информационными блоками.
 *
 * @method CmsSelector|BaseInfoBlock[] select() Возвращает селектор для выбора информационных блоков.
 * @method BaseInfoBlock get($guid, $localization = ILocalesService::LOCALE_CURRENT)  Возвращает информационный блок по GUID.
 * @method BaseInfoBlock getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает информационный блок по id.
 * @method BaseInfoBlock add($typeName = IObjectType::BASE) Создает и возвращает информационный блок.
 */
class InfoBlockCollection extends CmsCollection
{
    /**
     * Возвращает инфоблок по его названию.
     * @param string $infoBlockName название инфоблока
     * @return null|BaseInfoBlock
     */
    public function getByName($infoBlockName)
    {
        return $this->select()
            ->where(BaseInfoBlock::FIELD_INFOBLOCK_NAME)->equals($infoBlockName)
            ->result()
            ->fetch();
    }

    /**
     * Проверяет уникальность названия инфоблока.
     * @param BaseInfoBlock $infoBlock
     * @return bool
     */
    public function checkInfoblockNameUniqueness(BaseInfoBlock $infoBlock)
    {
        $infoBlocks = $this->getInternalSelector()
            ->fields([BaseInfoBlock::FIELD_IDENTIFY])
            ->where(BaseInfoBlock::FIELD_INFOBLOCK_NAME)
            ->equals($infoBlock->infoblockName)
            ->where(BaseInfoBlock::FIELD_IDENTIFY)
            ->notEquals($infoBlock->getId())
            ->getResult();

        return !count($infoBlocks);
    }
}
