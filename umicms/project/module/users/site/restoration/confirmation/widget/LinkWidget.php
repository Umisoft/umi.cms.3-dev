<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\restoration\confirmation\widget;

use umicms\hmvc\widget\BaseLinkWidget;

/**
 * Виджет вывода ссылки на подтверждение смены пароля.
 */
class LinkWidget extends BaseLinkWidget
{
    /**
     * {@inheritdoc}
     */
    public $absolute = true;
    /**
     * @var string $activationCode код активации
     */
    public $activationCode;

    /**
     * {@inheritdoc}
     */
    protected function getLinkUrl()
    {
        return $this->getUrl('index', ['activationCode' => $this->activationCode], $this->absolute);
    }

}
 