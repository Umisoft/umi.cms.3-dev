<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\tag\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseListWidget;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogTag;

/**
 * Виджет для вывода списка постов по тэгам.
 */
class TagPostListWidget extends BaseListWidget
{
    /**
     * {@inheritdoc}
     */
    public $template = 'postList';
    /**
     * @var array|BlogTag[]|BlogTag|null $tags тэг, список тэгов блога или GUID, из которых выводятся посты.
     * Если не указаны, то посты выводятся из всех тэгов
     */
    public $tags;

    /**
     * @var BlogModule $module модуль "Блоги"
     */
    protected $module;

    /**
     * Конструктор.
     * @param BlogModule $module модуль "Блоги"
     */
    public function __construct(BlogModule $module)
    {
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSelector()
    {
        $tags = (array) $this->tags;

        foreach ($tags as &$tag) {
            if (is_string($tag)) {
                $tag = $this->module->tag()->get($tag);
            }

            if (!$tag instanceof BlogTag) {
                throw new InvalidArgumentException(
                    $this->translate(
                        'Widget parameter "{param}" should be instance of "{class}".',
                        [
                            'param' => 'tags',
                            'class' => BlogTag::className()
                        ]
                    )
                );
            }
        }

        return $this->module->getPostByTag($tags);
    }

}
 