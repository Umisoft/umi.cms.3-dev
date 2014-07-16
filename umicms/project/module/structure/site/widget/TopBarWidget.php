<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\site\widget;

use umicms\hmvc\widget\BaseCmsWidget;

/**
 * Виджет вывода верхней панели сайта.
 */
class TopBarWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'topBar';

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->createResult($this->template);
    }
}
 