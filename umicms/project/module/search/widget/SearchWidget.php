<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\search\widget;

use umi\form\Form;
use umi\http\Request;
use umi\http\THttpAware;
use umicms\base\widget\BaseWidget;
use umicms\project\module\news\api\NewsPublicApi;
use umicms\project\module\search\api\SearchApi;

/**
 * Виджет вывода сюжета новостей
 */
class SearchWidget extends BaseWidget
{
    /**
     * @var string $formName
     */
    public $formName = 'search';
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'search/form';

    /**
     * @var NewsPublicApi $api API модуля "Новости"
     */
    protected $api;

    /**
     * @var Request $request
     */
    protected $request;

    /**
     * Конструктор.
     * @param SearchApi $searchApi API модуля "Новости"
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
                'form' => $form
            ]
        );
    }
}
