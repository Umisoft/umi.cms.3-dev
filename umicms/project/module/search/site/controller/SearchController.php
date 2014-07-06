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
use umicms\project\module\search\model\SearchApi;

/**
 * Контроллер запроса и вывода результатов поиска
 */
class SearchController extends BaseSitePageController
{
    /**
     * @var SearchApi $api модуль "Поиск"
     */
    protected $api;

    /**
     * Конструктор.
     * @param SearchApi $api
     */
    public function __construct(SearchApi $api)
    {
        $this->api = $api;
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
            $searchResults = $this->api->search($query);
        }

        return $this->createViewResponse(
            'index',
            [
                'page' => $this->getCurrentPage(),
                'form' => $form,
                'results' => $searchResults,
                'query' => $query,
            ]
        );
    }
}
