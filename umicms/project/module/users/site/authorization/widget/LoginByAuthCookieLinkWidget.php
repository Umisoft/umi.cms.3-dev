<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\authorization\widget;

use umi\hmvc\widget\BaseWidget;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\hmvc\widget\BaseCmsWidget;

/**
 * Виджет для отображения ссылки ресурс аутентификации по куке
 */
class LoginByAuthCookieLinkWidget extends BaseCmsWidget
{
    /**
     * @return string|\umi\hmvc\view\IView
     */
    public function __invoke()
    {
        return $this->getUrl('loginByAuthCookie');
    }
} 