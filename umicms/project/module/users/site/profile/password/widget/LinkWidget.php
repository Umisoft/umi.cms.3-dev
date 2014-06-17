<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\profile\password\widget;

use umicms\hmvc\widget\BaseLinkWidget;

/**
 * Виджет вывода ссылки на страницу изменения пароля.
 */
class LinkWidget extends BaseLinkWidget
{
    /**
     * {@inheritdoc}
     */
    protected function getLinkUrl()
    {
        return $this->getUrl('index', [], $this->absolute);
    }
}
 