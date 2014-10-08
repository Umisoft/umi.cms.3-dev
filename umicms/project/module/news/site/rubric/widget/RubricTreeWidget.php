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

use umicms\hmvc\widget\BaseTreeWidget;
use umicms\project\module\news\model\NewsModule;

/**
 * Виджет для вывода дерева новостных рубрик.
 */
class RubricTreeWidget extends BaseTreeWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'tree';

    /**
     * @var NewsModule $module модуль "Новости"
     */
    protected $module;

    /**
     * Конструктор.
     * @param NewsModule $module модуль "Новости"
     */
    public function __construct(NewsModule $module)
    {
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollection()
    {
        return $this->module->rubric();
    }
}
 