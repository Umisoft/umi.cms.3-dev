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
use umicms\base\object\ICmsObject;
use umicms\base\widget\BaseWidget;
use umicms\project\module\news\api\NewsPublicApi;
use umicms\project\module\search\api\SearchApi;

/**
 * Виджет вывода сюжета новостей
 */
class SearchFragmentsWidget extends BaseWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'search/fragments';
    /**
     * @var ICmsObject $result
     */
    public $result;
    /**
     * @var string $query
     */
    public $query;

    /**
     * @var NewsPublicApi $api API модуля "Новости"
     */
    protected $api;

    /**
     * Конструктор.
     * @param SearchApi $searchApi API модуля "Новости"
     * @internal param \umi\http\Request $request
     */
    public function __construct(SearchApi $searchApi)
    {
        $this->api = $searchApi;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $content = $this->api->extractSearchableContent($this->result);
        return $this->createResult(
            $this->template,
            [
                'fragmenter' => $this->api->getResultFragmented($this->query, $content)
            ]
        );
    }
}
