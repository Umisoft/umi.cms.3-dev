<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\site\infoblock\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseAccessRestrictedWidget;
use umicms\project\module\structure\api\object\InfoBlock;
use umicms\project\module\structure\api\StructureModule;

/**
 * Виджет для вывода информационного блока.
 */
class ViewWidget extends BaseAccessRestrictedWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет.
     */
    public $template = 'logo';
    /**
     * @var InfoBlock $infoBlock информационный блок или GUID
     */
    public $infoBlock = '87f20300-197a-4309-b86b-cbe8ebcc358d';
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
        if (is_string($this->infoBlock)) {
            $this->infoBlock = $this->api->infoBlock()->get($this->infoBlock);
        }

        if (!$this->infoBlock instanceof InfoBlock) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'infoBlock',
                        'class' => InfoBlock::className()
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
