<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\author\widget;

use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\blog\api\BlogModule;

/**
 * Виджет для вывода списка авторов.
 */
class BlogAuthorListWidget extends BaseSecureWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'list';
    /**
     * @var int $limit максимальное количество выводимых авторов.
     * Если не указано, выводятся все авторы.
     */
    public $limit;

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
        return $this->createResult(
            $this->template,
            [
                'authors' => $this->api->getAuthors()
            ]
        );
    }
}
 