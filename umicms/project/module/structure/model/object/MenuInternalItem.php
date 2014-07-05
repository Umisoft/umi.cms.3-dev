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

use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsObject;

/**
 * Класс описывающий пункт меню на внутренний ресурс.
 *
 * @property string $pageRelation ссылка на страницу
 */
class MenuInternalItem extends MenuItem implements ICollectionManagerAware
{
    use TCollectionManagerAware;

    /**
     * Тип объекта
     */
    const TYPE = 'internalItem';
    /**
     * Тип объекта
     * @var string $itemType
     */
    protected $itemType = 'internalItem';
    /**
     *  Имя поля для хранения ссылки на страницу сайта
     */
    const FIELD_PAGE_RELATION = 'pageRelation';

    /**
     * Возвращает ссылку на внутренний ресурс.
     * @return string|null
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
}
 