<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\search\site\controller;

use umi\form\element\IFormElement;
use umi\http\Response;
use umicms\hmvc\component\site\BaseSitePageController;
use umicms\project\module\search\model\SearchModule;

/**
 * Контроллер вывода страницы поиска.
 */
class IndexController extends BaseSitePageController
{
    /**
     * @var SearchModule $api модуль "Поиск"
     */
    protected $module;

    /**
     * Конструктор.
     * @param SearchModule $module
     */
    public function __construct(SearchModule $module)
    {
        $this->module = $module;
    }

    /**
     * Формирует результат работы контроллера.
     *
     * Для шаблонизации доступны следущие параметры:
     * @templateParam umi\form\FormEntityView $form представление формы
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница поиска
     * @templateParam string $query текущий поисковый запрос
     * @templateParam string $encodedQuery URL-закодированный текущий поисковый запрос
     *
     * @return Response
     */
    public function __invoke()
    {
        $form = $this->getComponent()->getForm('search');
        $form->setData($this->getAllQueryVars());
        /**
         * @var IFormElement $queryInput
         */
        $queryInput = $form->get('query');
        $query = $queryInput->getValue();

        return $this->createViewResponse(
            $this->template,
            [
                'page' => $this->getCurrentPage(),
                'form' => $form->getView(),
                'query' => $query,
                'encodedQuery' => urlencode($query)
            ]
        );
    }
}
