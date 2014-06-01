<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\tag\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseAccessRestrictedWidget;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogTag;

/**
 * Виджет для вывода списка постов по тэгам.
 */
class TagPostListWidget extends BaseAccessRestrictedWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'postList';
    /**
     * @var array|BlogTag[]|BlogTag|null $tags тэг, список тэгов блога или GUID, из которых выводятся посты.
     * Если не указаны, то посты выводятся из всех тэгов
     */
    public $tags;

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
    public function __invoke()
    {
        $tags = (array) $this->tags;

        foreach ($tags as &$tag) {
            if (is_string($tag)) {
                $tag = $this->api->tag()->get($tag);
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

        return $this->createResult(
            $this->template,
            [
                'posts' => $this->api->getPostByTag($tags)
            ]
        );
    }
}
 