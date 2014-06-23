<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\widget;

/**
 * Базовый класс виджета вывода ссылки
 */
abstract class BaseLinkWidget extends BaseWidget
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
 