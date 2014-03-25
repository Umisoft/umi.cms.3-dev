<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\api;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umicms\exception\InvalidArgumentException;
use umicms\project\module\structure\api\object\StructureElement;
use umicms\project\site\callstack\IPageCallStackAware;
use umicms\project\site\callstack\TPageCallStackAware;

/**
 * API для работы с автогенерируемым меню структуры
 */
class AutoMenu implements ILocalizable, IPageCallStackAware
{
    use TLocalizable;
    use TPageCallStackAware;

    /**
     * @var StructureApi $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param StructureApi $api
     */
    public function __construct(StructureApi $api)
    {
        $this->api = $api;
    }

    /**
     * Строит меню.
     * @param StructureElement|null $branch ветка, внутри которой строится меню
     * @param int $depth глубина вложенности меню
     * @throws InvalidArgumentException
     * @return array массив в формате [['page' => StructureElement, 'active' => bool, 'children' => [...]], ...]
     */
    public function buildMenu(StructureElement $branch = null, $depth = 1)
    {
        $depth = intval($depth);
        if ($depth < 1) {
            throw new InvalidArgumentException($this->translate(
                'Cannot build menu. Invalid argument "depth" value.'
            ));
        }

        return $this->getMenuItems($branch, $depth - 1);
    }

    /**
     * Возвращет массив для построения меню.
     * @param StructureElement|null $branch страница, от которой строится меню
     * @param int $depth количество уровней, на которые нужно построить подменю
     * @return array массив в формате [['page' => StructureElement, 'active' => bool, 'children' => [...]], ...]
     */
    protected function getMenuItems(StructureElement $branch = null, $depth = 0)
    {
        $menu = [];
        $menuItems = $this->api->element()->selectChildren($branch)
            ->where(StructureElement::FIELD_IN_MENU)->equals(true);

        foreach ($menuItems as $page) {
            $pageInfo = ['page' => $page];
            $pageInfo['active'] = $this->hasPage($page);
            $pageInfo['current'] = ($this->hasCurrentPage() && $this->getCurrentPage() == $page);
            if ($depth && ($this->checkIfSubmenuAlwaysShown($page) || $this->checkIfCurrentSubmenuShown($page))) {
                $pageInfo['children'] = $this->getMenuItems($page, $depth - 1);
            } else {
                $pageInfo['children'] = [];
            }
            $menu[] = $pageInfo;
        }
        return $menu;
    }

    /**
     * Проверяет, выводить ли всегда подменю для страницы
     * @param StructureElement $page
     * @return bool
     */
    private function checkIfSubmenuAlwaysShown(StructureElement $page)
    {
        return $page->submenuState == StructureElement::SUBMENU_ALWAYS_SHOWN;
    }

    /**
     * Проверяет, выводить ли всегда подменю для страницы
     * @param StructureElement $page
     * @return bool
     */
    private function checkIfCurrentSubmenuShown(StructureElement $page)
    {
        if ($page->submenuState == StructureElement::SUBMENU_CURRENT_SHOWN) {
            if ($this->hasPage($page)) {
                return true;
            }
        }
        return false;
    }
}
 