<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\site\menu\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\view\CmsView;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\orm\object\ICmsPage;
use umicms\project\module\structure\model\object\StructureElement;
use umicms\project\module\structure\model\StructureModule;

/**
 * Виджет для вывода автогенерируемого меню
 */
class AutoMenuWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет.
     */
    public $template = 'auto';
    /**
     * @var string|StructureElement $branch ветка или GUID, от которой строится меню. Если не задано, меню строится от корня сайта.
     */
    public $branch;
    /**
     * @var int $depth уровень вложенности меню.
     */
    public $depth = 1;
    /**
     * @var bool $fullyLoad признак необходимости загружать все свойства объектов списка.
     * Список полей для загрузки при значении true игнорируется.
     */
    public $fullyLoad;
    /**
     * @var string $fields список имен полей, с которыми нужно загрузить объекты, в виде строки с разделителем в виде запятой
     */
    public $fields;

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
     * Формирует результат работы виджета.
     *
     * Для шаблонизации доступны следущие параметры:
     * <ul>
     * <li> array $menu элементы меню в формате:
     * [
     *  [
     *    'page' => StructureElement $page,
     *    'active' => bool страница находится в списке хлебных крошек для текущей странице ,
     *    'current' => bool страница является текущей,
     *    'children' => array список дочерних элементов меню
     *  ],
     *  ...
     * ]
     * </li>
     * </ul>
     *
     * @throws InvalidArgumentException
     * @return CmsView
     */
    public function __invoke()
    {
        if (is_string($this->branch)) {
            $this->branch = $this->module->element()->get($this->branch);
        }

        if (isset($this->branch) && !$this->branch instanceof StructureElement) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'branch',
                        'class' => StructureElement::className()
                    ]
                )
            );
        }

        $fields = [];
        if (!$this->fullyLoad) {
            $fields = ICmsPage::FIELD_DISPLAY_NAME;
            if ($this->fields) {
                $fields = $fields . ',' . $this->fields;
            }
            $fields = explode(',', $fields);
        }

        return $this->createResult(
            $this->template,
            [
                'menu' => $this->module->autoMenu()->buildMenu($this->branch, $this->depth, $fields)
            ]
        );
    }
}
