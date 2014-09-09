<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\templating\helper;

use umicms\hmvc\url\IUrlManager;

/**
 * Помощник для формирования якорных ссылок.
 */
class AnchorHelper
{
    /**
     * @var IUrlManager $urlManager менеджер ссылок
     */
    protected $urlManager;

    /**
     * Конструктор.
     * @param IUrlManager $urlManager менеджер ссылок
     */
    public function __construct(IUrlManager $urlManager)
    {
        $this->urlManager = $urlManager;
    }

    /**
     * Формирует якорную ссылку.
     * @param string $anchorName имя якоря
     * @return string
     */
    public function buildAnchorLink($anchorName = '')
    {
        return $this->urlManager->getCurrentUrl() . '#' . $anchorName;
    }
}
 