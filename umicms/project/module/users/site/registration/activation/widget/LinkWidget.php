<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\site\registration\activation\widget;

use umicms\hmvc\widget\BaseLinkWidget;

/**
 * Виджет вывода ссылки на активацию пользователя.
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
 