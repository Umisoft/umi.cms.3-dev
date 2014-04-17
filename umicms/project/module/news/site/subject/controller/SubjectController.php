<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\subject\controller;

use umicms\project\site\controller\SitePageController;
use umicms\project\module\news\api\NewsModule;

/**
 * Контроллер отображения новостного сюжета
 */
class SubjectController extends SitePageController
{
    /**
     * @var NewsModule $api
     */
    protected $api;

    public function __construct(NewsModule $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $slug = $this->getRouteVar('slug');
        $subject = $this->api->subject()->getByUri($slug);

        $this->pushCurrentPage($subject);

        return $this->createViewResponse(
            'view',
            [
                'subject' => $subject
            ]
        );
    }
}
 