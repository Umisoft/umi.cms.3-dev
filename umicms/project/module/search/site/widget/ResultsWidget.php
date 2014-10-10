<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\search\site\widget;

use umicms\hmvc\widget\BaseListWidget;
use umicms\project\module\search\model\SearchModule;

/**
 * Виджет для вывода результатов поиска
 */
class ResultsWidget extends BaseListWidget
{
    /**
     * @var string $query строка поиска
     */
    public $query;
    /**
     * {@inheritdoc}
     */
    public $template = 'results';

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
    protected function getSelector()
    {
        return $this->module->getSearchApi()->search($this->query);
    }

    /**
     * {@inheritdoc}
     */
    protected function createResult($templateName, array $variables = [])
    {
        $variables['query'] = $this->query;
        $variables['encodedQuery'] = urlencode($this->query);

        return parent::createResult($templateName, $variables);
    }
}
 