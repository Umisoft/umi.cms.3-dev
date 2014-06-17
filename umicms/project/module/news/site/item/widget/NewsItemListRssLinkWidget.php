<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\site\item\widget;

use umicms\hmvc\widget\BaseLinkWidget;

/**
 * Виджет для вывода URL на RSS-ленту по рубрике.
 */
class NewsItemListRssLinkWidget extends BaseLinkWidget
{
    /**
     * {@inheritdoc}
     */
    public $template = 'rssLink';

    /**
     * {@inheritdoc}
     */
    protected function getLinkUrl()
    {
        return $this->getUrl('rss');
    }
}
 