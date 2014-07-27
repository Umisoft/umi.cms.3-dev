<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\site\infoblock\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\view\CmsView;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\module\structure\model\object\BaseInfoBlock;
use umicms\project\module\structure\model\StructureModule;

/**
 * Виджет для вывода информационного блока.
 */
class ViewWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет.
     */
    public $template = 'infoblock';
    /**
     * @var BaseInfoBlock $infoBlock информационный блок или его название
     */
    public $infoBlock;
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
     * @templateParam umicms\project\module\structure\model\object\BaseInfoBlock $infoBlock информационный блок
     *
     * @throws InvalidArgumentException
     * @return CmsView
     */
    public function __invoke()
    {
        if (is_string($this->infoBlock)) {
            $this->infoBlock = $this->module->infoBlock()->getByName($this->infoBlock);
        }

        if (!$this->infoBlock instanceof BaseInfoBlock) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'infoBlock',
                        'class' => BaseInfoBlock::className()
                    ]
                )
            );
        }

        return $this->createResult(
            $this->template,
            [
                'infoBlock' => $this->infoBlock
            ]
        );
    }
}
