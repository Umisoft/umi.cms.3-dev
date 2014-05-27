<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\rubric\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseWidget;
use umicms\project\module\news\api\NewsModule;
use umicms\project\module\news\api\object\NewsRubric;

/**
 * Виджет для вывода дерева новостных рубрик.
 */
class RubricTreeWidget extends BaseWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'tree';

    /**
     * @var string|null|NewsRubric $parentRubric новостная рубрика или GUID, из которой выводятся дочерние рубрики.
     * Если не указан, выводятся все корневые рубрики.
     */
    public $parentRubric;

    /**
     * @var NewsModule $api API модуля "Новости"
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsModule $newsApi API модуля "Новости"
     */
    public function __construct(NewsModule $newsApi)
    {
        $this->api = $newsApi;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        if (is_string($this->parentRubric)) {
            $this->parentRubric = $this->api->rubric()->get($this->parentRubric);
        }

        if (isset($this->parentRubric) && !$this->parentRubric instanceof NewsRubric) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'parentRubric',
                        'class' => 'umicms\project\module\news\api\object\NewsRubric'
                    ]
                )
            );
        }

        return $this->createTreeResult($this->template, $this->api->rubric()->select());
    }
}
 