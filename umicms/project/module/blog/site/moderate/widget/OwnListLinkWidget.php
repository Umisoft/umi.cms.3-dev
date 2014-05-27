<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\moderate\widget;

use umicms\hmvc\widget\BaseLinkWidget;

/**
 * Виджет для вывода ссылки на спискок постов текущего автора, требующих модерации.
 */
class OwnListLinkWidget extends BaseLinkWidget
{
    /**
     * {@inheritdoc}
     */
    public $template = 'moderateLink';

    /**
     * {@inheritdoc}
     */
    protected function getLinkUrl()
    {
        return $this->getUrl('index', [], $this->absolute);
    }
}
 