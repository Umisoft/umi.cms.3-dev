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

use umi\form\Form;
use umi\http\Request;
use umi\http\THttpAware;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\module\search\model\SearchApi;

/**
 * Виджет, выводящий форму поиска.
 */
class SearchWidget extends BaseCmsWidget
{
    /**
     * Имя формы
     * @var string $formName
     */
    public $formName = 'search';
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'search/form';
    /**
     * Поисковый запрос
     * @var string $query
     */
    public $query;

    /**
     * @var SearchApi $api модуль "Поиск"
     */
    protected $api;

    /**
     * Http-запрос, используемый для поиска
     * @var Request $request
     */
    protected $request;

    /**
     * Конструктор.
     * @param SearchApi $searchApi модуль "Поиск"
     * @param Request $request
     */
    public function __construct(SearchApi $searchApi, Request $request)
    {
        $this->api = $searchApi;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $form = new Form($this->formName, [
            'query'
        ]);

        return $this->createResult(
            $this->template,
            [
                'form' => $form,
                'formName' => $this->formName,
                'query'=>$this->query
            ]
        );
    }
}
