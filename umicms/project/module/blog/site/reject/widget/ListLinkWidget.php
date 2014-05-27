<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\reject\widget;

use umicms\hmvc\widget\BaseLinkWidget;

/**
 * Виджет для вывода ссылки на спискок отклонённых постов текущего автора.
 */
class ListLinkWidget extends BaseLinkWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'listLink';

    /**
     * {@inheritdoc}
     */
    protected function getLinkUrl()
    {
        return $this->getUrl('index', [], $this->absolute);
    }
}
 