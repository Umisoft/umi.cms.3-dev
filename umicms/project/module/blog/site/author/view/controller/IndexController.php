<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\author\view\controller;

use umicms\hmvc\component\site\BaseSitePageController;
use umicms\project\module\blog\model\BlogModule;

class IndexController extends BaseSitePageController
{
    /**
     * @var BlogModule $api
     */
    private $api;

    /**
     * Конструктор.
     */
    public function __construct(BlogModule $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        // todo: добавить проверку существования текущего автора, если он отсутствует - создать нового
        return $this->createViewResponse(
            'index',
            [
                'page' => $this->api->getCurrentAuthor()
            ]
        );
    }
}
 