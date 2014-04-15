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
use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\news\api\NewsModule;
use umicms\project\module\news\api\object\NewsRubric;

/**
 * Виджет вывода рубрики
 */
class RubricWidget extends BaseSecureWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'view';
    /**
     * @var string|NewsRubric $rubric рубрика или GUID рубрики
     */
    public $rubric;

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
        if (is_string($this->rubric)) {
            $this->rubric = $this->api->rubric()->get($this->rubric);
        }

        if (isset($this->rubric) && !$this->rubric instanceof NewsRubric) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param} should be instance of "{class}".',
                    [
                        'param' => 'rubric',
                        'class' => 'NewsRubric'
                    ]
                )
            );
        }

        return $this->createResult(
            $this->template,
            [
                'rubric' => $this->rubric
            ]
        );
    }
}
 