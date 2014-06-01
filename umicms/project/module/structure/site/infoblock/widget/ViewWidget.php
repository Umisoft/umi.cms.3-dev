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
use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\structure\api\object\InfoBlock;
use umicms\project\module\structure\api\StructureModule;

/**
 * Виджет для вывода информационного блока.
 */
class ViewWidget extends BaseSecureWidget
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
