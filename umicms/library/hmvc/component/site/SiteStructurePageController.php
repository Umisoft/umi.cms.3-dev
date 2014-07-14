<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\site;

use umi\http\Response;

/**
 * Контроллер вывода системных страниц компонентов.
 */
class SiteStructurePageController extends BaseSitePageController
{
    /**
     * Формирует результат работы контроллера.
     *
     * Для шаблонизации доступны следущие параметры:
     * <ul>
     * <li> ICmsPage $page текущая страница </li>
     * </ul>
     *
     * @return Response
     */
    public function __invoke()
    {
        return $this->createViewResponse(
            $this->template,
            [
                'page' => $this->getCurrentPage()
            ]
        );
    }
}
 