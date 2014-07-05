<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\site\menu\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseTreeWidget;
use umicms\project\module\structure\model\object\Menu;
use umicms\project\module\structure\model\StructureModule;

/**
 * Виджет для вывода настраиваемого меню.
 */
class CustomMenuWidget extends BaseTreeWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет.
     */
    public $template = 'customMenu';

    /**
     * @var string $menuName имя выводимого меню.
     */
    public $menuName;

    /**
     * @var StructureModule $module
     */
    protected $module;

    /**
     * Конструктор.
     * @param StructureModule $module
     */
    public function __construct(StructureModule $module)
    {
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSelector()
    {
        $menu = $this->module->menu()->select()
            ->where(Menu::FIELD_NAME)->equals($this->menuName)
            ->result()
            ->fetch();

        if (!$menu instanceof Menu) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'menuName',
                        'class' => Menu::className()
                    ]
                )
            );
        }

        $this->parentNode = $menu;

        return $this->module->menu()->select();
    }
}
