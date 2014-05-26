<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\site\profile\password\widget;

use umicms\hmvc\widget\BaseSecureWidget;

/**
 * Виджет вывода ссылки на страницу изменения пароля.
 */
class LinkWidget extends BaseSecureWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'link';
    /**
     * @var bool $absolute генерировать ли абсолютный URL для ссылки
     */
    public $absolute = false;

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->createResult(
            $this->template,
            [
                'url' => $this->getUrl('index', [], $this->absolute)
            ]
        );
    }
}
 