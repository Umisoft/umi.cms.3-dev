<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\author\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogAuthor;

/**
 * Виджет для вывода списка постов по автору.
 */
class BlogAuthorPostListWidget extends BaseSecureWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'postList';
    /**
     * @var int $limit максимальное количество выводимых постов.
     * Если не указано, выводятся все посты.
     */
    public $limit;
    /**
     * @var array|BlogAuthor[]|BlogAuthor|null $blogAuthor авторы, список авторов блога или GUID, посты которых выводятся.
     * Если не указаны, то посты выводятся всех авторов
     */
    public $blogAuthors;

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
        $blogAuthors = (array) $this->blogAuthors;

        foreach ($blogAuthors as &$blogAuthor) {
            if (is_string($blogAuthor)) {
                $blogAuthor = $this->api->author()->get($blogAuthor);
            }

            if (isset($blogAuthor) && !$blogAuthor instanceof BlogAuthor) {
                throw new InvalidArgumentException(
                    $this->translate(
                        'Widget parameter "{param}" should be instance of "{class}".',
                        [
                            'param' => 'blogAuthors',
                            'class' => BlogAuthor::className()
                        ]
                    )
                );
            }
        }

        return $this->createResult(
            $this->template,
            [
                'posts' => $this->api->getPostsByAuthor($blogAuthors)
            ]
        );
    }
}
 