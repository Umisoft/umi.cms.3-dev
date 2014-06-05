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
use umicms\project\module\structure\api\object\Menu;
use umicms\project\module\structure\api\StructureModule;

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
     * {@inheritdoc}
     */
    protected function getSelector()
    {
        $menu = $this->api->menu()->select()
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

        return $this->api->menu()->select();
    }
}
