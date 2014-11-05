<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\model\object;

use umicms\orm\object\CmsHierarchicObject;

/**
 * Класс описывающий пункт меню на сторонний ресурс.

 * @property string $resourceUrl ссылка на сторонний ресурс
 */
class MenuExternalItem extends CmsHierarchicObject implements IMenuItem
{
    /**
     * Тип объекта
     */
    const TYPE = 'externalItem';
    /**
     * Имя поля для хранения ссылки на сторонний ресурс
     */
    const FIELD_RESOURCE_URL = 'resourceUrl';

    /**
     * @var string $itemType тип элемента меню
     */
    protected $itemType = 'externalItem';

    /**
     * {@inheritdoc}
     */
    public function getItemUrl()
    {
        return $this->resourceUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemType()
    {
        return $this->itemType;
    }
}
 