<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\moderate\controller;

use umicms\hmvc\controller\site\BaseSitePageController;

/**
 * Контроллер вывода списка всех постов, требующих модерирование.
 */
class PostListController extends BaseSitePageController
{
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->createViewResponse(
            'all'
        );
    }
}

 