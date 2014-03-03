<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\subject\controller;

use umicms\hmvc\controller\BaseController;
use umicms\project\module\news\api\NewsPublicApi;

/**
 * Контроллер отображения новостного сюжета
 */
class SubjectController extends BaseController
{
    /**
     * @var NewsPublicApi $api
     */
    protected $api;

    public function __construct(NewsPublicApi $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $slug = $this->getRouteVar('slug');
        $subject = $this->api->subject()->getBySlug($slug);

        return $this->createViewResponse(
            'view',
            [
                'subject' => $subject
            ]
        );
    }
}
 