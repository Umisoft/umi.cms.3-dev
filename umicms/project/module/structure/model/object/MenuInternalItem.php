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

use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsPage;

/**
 * Класс описывающий пункт меню на внутренний ресурс.
 *
 * @property ICmsPage $pageRelation связанная страница
 */
class MenuInternalItem extends CmsHierarchicObject implements IMenuItem, IActiveAccessibleObject
{
    /**
     * Тип объекта
     */
    const TYPE = 'internalItem';
    /**
     * @var string $itemType тип элемента меню
     */
    protected $itemType = 'internalItem';
    /**
     *  Имя поля для хранения связанной страницы
     */
    const FIELD_PAGE_RELATION = 'pageRelation';

    /**
     * {@inheritdoc}
     */
    public function getItemUrl()
    {
        try {
            $menuItem = $this->pageRelation;
        } catch (\Exception $e) {
            return null;
        }

        if ($menuItem instanceof ICmsPage) {
            return $menuItem->getPageUrl();
        } else {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemType()
    {
        return $this->itemType;
    }
}
 