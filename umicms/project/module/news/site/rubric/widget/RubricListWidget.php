<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\site\rubric\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseListWidget;
use umicms\project\module\news\model\NewsModule;
use umicms\project\module\news\model\object\NewsRubric;

/**
 * Виджет для вывода списка новостных рубрик.
 */
class RubricListWidget extends BaseListWidget
{
    /**
     * @var string|null|NewsRubric $parentRubric новостная рубрика или GUID, из которой выводятся дочерние рубрики.
     * Если не указан, выводятся все корневые рубрики.
     */
    public $parentRubric;

    /**
     * @var NewsModule $module модуль "Новости"
     */
    protected $module;

    /**
     * Конструктор.
     * @param NewsModule $newsApi модуль "Новости"
     */
    public function __construct(NewsModule $newsApi)
    {
        $this->module = $newsApi;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSelector()
    {
        if (is_string($this->parentRubric)) {
            $this->parentRubric = $this->module->rubric()->get($this->parentRubric);
        }

        if (isset($this->parentRubric) && !$this->parentRubric instanceof NewsRubric) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'parentRubric',
                        'class' => NewsRubric::className()
                    ]
                )
            );
        }

        return $this->module->getRubrics($this->parentRubric);
    }
}
 