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
use umicms\hmvc\component\site\BaseSitePageController;
use umicms\project\module\search\model\SearchModule;

/**
 * Контроллер запроса и вывода результатов поиска
 */
class SearchController extends BaseSitePageController
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
     * {@inheritdoc}
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

        $searchResults = null;
        if ($query) {
            $searchResults = $this->module->getSearchApi()->search($query);
        }

        return $this->createViewResponse(
            'index',
            [
                'page' => $this->getCurrentPage(),
                'form' => $form->getView(),
                'searchResults' => $searchResults,
                'query' => $query,
            ]
        );
    }
}
