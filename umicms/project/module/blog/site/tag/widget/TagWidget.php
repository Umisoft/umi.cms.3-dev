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
use umicms\hmvc\widget\BaseAccessRestrictedWidget;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogTag;

/**
 * Виджет вывода тэга.
 */
class TagWidget extends BaseAccessRestrictedWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'page';
    /**
     * @var string|BlogTag $BlogTag GUID тэга
     */
    public $blogTag;

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
        if (is_string($this->blogTag)) {
            $this->blogTag = $this->api->tag()->get($this->blogTag);
        }

        if (!$this->blogTag instanceof BlogTag) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'blogTag',
                        'class' => BlogTag::className()
                    ]
                )
            );
        }

        return $this->createResult(
            $this->template,
            [
                'blogTag' => $this->blogTag
            ]
        );
    }
}
 