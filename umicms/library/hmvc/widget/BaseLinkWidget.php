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

use umicms\hmvc\view\CmsView;

/**
 * Базовый класс виджета вывода ссылки
 */
abstract class BaseLinkWidget extends BaseCmsWidget
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
     * Формирует результат работы виджета.
     *
     * Для шаблонизации доступны следущие параметры:
     * <ul>
     * <li> string $url URL ссылки </li>
     * </ul>
     *
     * @return CmsView
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
 