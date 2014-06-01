<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\widget;

/**
 * Базовый класс виджета вывода ссылки
 */
abstract class BaseLinkWidget extends BaseAccessRestrictedWidget
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
     * Возвращает ссылку для отображения
     * @return string
     */
    abstract protected function getLinkUrl();

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->createResult(
            $this->template,
            [
                'url' => $this->getLinkUrl()
            ]
        );
    }
}
 