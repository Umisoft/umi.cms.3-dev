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

class LoginByAuthCookieLinkWidget extends BaseWidget implements IUrlManagerAware
{
    use TUrlManagerAware;

    public function __invoke()
    {
        $url = rtrim($this->getUrlManager()->getProjectUrl(), '/');
        $url .= $this->getContext()->getBaseUrl();
        $url .= $this->getComponent()->getRouter()->assemble('loginByAuthCookie');

        if ($postfix = $this->getUrlManager()->getSiteUrlPostfix()) {
            $url .= '.' . $postfix;
        }

        return $url;
    }
} 