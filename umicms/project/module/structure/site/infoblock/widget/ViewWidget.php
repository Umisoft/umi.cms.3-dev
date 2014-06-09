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

use umi\acl\IAclResource;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseWidget;
use umicms\project\module\structure\api\object\BaseInfoBlock;
use umicms\project\module\structure\api\StructureModule;

/**
 * Виджет для вывода информационного блока.
 */
class ViewWidget extends BaseWidget implements IAclResource
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
            $this->infoBlock = $this->api->infoBlock()->getByName($this->infoBlock);
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
