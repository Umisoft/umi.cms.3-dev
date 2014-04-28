<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\moderate\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;

/**
 * Виджет для вывода ссылки на редактирование поста, требующего модерации.
 */
class EditPostLinkWidget extends BaseSecureWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'editPostLink';
    /**
     * @var BlogPost $blogModerate пост или GUID редактируемого поста, требующего модерации
     */
    public $blogModerate;
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
        if (is_string($this->blogModerate)) {
            $this->blogModerate = $this->api->post()->getNeedModeratePost($this->blogModerate);
        }

        if (isset($this->blogModerate) && !$this->blogModerate instanceof BlogPost) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'blogModerate',
                        'class' => 'BlogPost'
                    ]
                )
            );
        }

        $url = $this->blogModerate->getId();
        return $this->createResult(
            $this->template,
            [
                'url' => $this->getUrl('edit', ['id' => $url])
            ]
        );
    }
}
 