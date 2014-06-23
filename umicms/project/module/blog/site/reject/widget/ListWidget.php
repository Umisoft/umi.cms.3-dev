<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\reject\widget;

use umi\acl\IAclResource;
use umicms\hmvc\widget\BaseListWidget;
use umicms\project\module\blog\api\BlogModule;

/**
 * Виджет для вывода списка отклонённых постов текущего автора.
 */
class ListWidget extends BaseListWidget implements IAclResource
{
    /**
     * @var BlogModule $api API модуля "Блоги"
     */
    protected $api;

    /**
     * Конструктор.
     * @param BlogModule $blogModule API модуля "Блоги"
     */
    public function __construct(BlogModule $blogModule)
    {
        $this->api = $blogModule;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSelector()
    {
        return $this->api->getOwnRejected();
    }
}
 