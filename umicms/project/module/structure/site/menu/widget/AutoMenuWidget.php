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
use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\structure\api\object\StructureElement;
use umicms\project\module\structure\api\StructureModule;

/**
 * Виджет для вывода автогенерируемого меню
 */
class AutoMenuWidget extends BaseSecureWidget
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
    public function __invoke()
    {
        if (is_string($this->branch)) {
            $this->branch = $this->api->element()->get($this->branch);
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

        return $this->createResult(
            $this->template,
            [
                'menu' => $this->api->menu()->buildMenu($this->branch, $this->depth)
            ]
        );
    }
}
