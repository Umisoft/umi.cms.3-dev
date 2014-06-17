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

use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\module\blog\model\BlogModule;

/**
 * Виджет для вывода облака тэгов.
 */
class TagCloudWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'cloud';
    /**
     * @var int $minFontSize минимальный размер шрифта
     */
    public $minFontSize = '13';
    /**
     * @var int $maxFontSize максимальный размер шрифта
     */
    public $maxFontSize = '24';
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
    public function __invoke()
    {
        $tags = $this->module->getTagCloud($this->minFontSize, $this->maxFontSize);
        return $this->createResult(
            $this->template,
            [
                'tags' => $tags
            ]
        );
    }
}
 