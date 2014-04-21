<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\draft\controller;

use umi\form\IFormAware;
use umi\form\TFormAware;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\hmvc\controller\BaseSecureController;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;

/**
 * Контроллер редактирования черновика блога.
 */
class BlogDraftPageController extends BaseSecureController implements IFormAware, IObjectPersisterAware
{
    use TFormAware;
    use TObjectPersisterAware;

    public $template = 'blogDraft';
    /**
     * @var BlogModule $api API модуля "Блоги"
     */
    protected $api;
    /**
     * @var BlogPost $blogDraft пост или GUID редактируемого черновика
     */
    protected $blogDraft;

    /**
     * Конструктор.
     * @param BlogModule $blogModule API модуля "Блоги"
     */
    public function __construct(BlogModule $blogModule)
    {
        $this->api = $blogModule;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $uri = $this->getRouteVar('uri');
        $page = $this->api->post()->getDraftByUri($uri);

        //$this->pushCurrentPage($page);

        return $this->createViewResponse(
            'page',
            [
                'page' => $page
            ]
        );
    }
}
 