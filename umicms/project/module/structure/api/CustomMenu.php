<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\api;

use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\structure\api\object\BaseMenu;
use umicms\project\module\structure\api\object\Menu;
use umicms\project\module\structure\api\object\MenuExternalItem;
use umicms\project\module\structure\api\object\MenuInternalItem;

/**
 * Api для работы с настраиваемым меню.
 */
class CustomMenu implements ICollectionManagerAware
{
    use TCollectionManagerAware;
    /**
     * @var StructureModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param StructureModule $api
     */
    public function __construct(StructureModule $api)
    {
        $this->api = $api;
    }

    /**
     * Возвращает меню.
     * @param string $name имя меню
     * @return CmsSelector|BaseMenu[]
     */
    protected function getMenuByName($name)
    {
        $menu = $this->api->menu()->select()
            ->where(Menu::FIELD_NAME)->equals($name)
            ->result()
            ->fetch();

        $items = $this->api->menu()->emptySelect();

        if ($menu instanceof CmsHierarchicObject) {
            $items = $this->api->menu()->selectDescendants($menu);
        }

        return $items;
    }

    /**
     * Строит меню.
     * @param string $name имя меню
     * @return array
     */
    public function buildMenu($name)
    {
        $items = $this->getMenuByName($name)->result()->fetchAll();

        $menuItems = [];
        $url = null;

        foreach ($items as $item) {

            if ($item instanceof MenuInternalItem) {
                $menuItem = $this->getCollectionManager()
                    ->getCollection($item->collectionNameItem)
                    ->getById($item->itemId);

                if ($menuItem instanceof ICmsPage) {
                    $url = $menuItem->getPageUrl();
                }
            }

            if ($item instanceof MenuExternalItem) {
                $url = $item->urlResource;
            }

            $menuItems[$item->parent->getId()][$item->getId()] = [
                'displayName' => $item->displayName,
                'url' => $url
            ];
        }

        return $menuItems;
    }

}
 