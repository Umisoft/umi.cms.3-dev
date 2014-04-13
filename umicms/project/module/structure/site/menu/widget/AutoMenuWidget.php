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
use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\structure\api\object\StructureElement;
use umicms\project\module\structure\api\StructureApi;

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
     * @var string|StructureElement $branch ветки или GUID, от которой строится меню. Если не задано, меню строится от корня сайта.
     */
    public $branch;

    /**
     * @var int $depth уровень вложенности меню.
     */
    public $depth = 1;

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
                    'Widget parameter "{param} should be instance of "{class}".',
                    [
                        'param' => 'branch',
                        'class' => 'StructureElement'
                    ]
                )
            );
        }

        return $this->createResult(
            $this->template,
            [
                'menu' => $this->api->structureMenu()->buildMenu($this->branch, $this->depth)
            ]
        );
    }
}
