<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\component;

use umi\hmvc\dispatcher\IDispatchContext;
use umi\http\Request;
use umicms\exception\RuntimeException;
use umicms\hmvc\component\BaseComponent;
use umicms\orm\object\ICmsPage;
use umicms\project\module\structure\api\StructureApi;
use umicms\project\site\callstack\IPageCallStackAware;
use umicms\project\site\callstack\TPageCallStackAware;

/**
 * Компонент сайта.
 */
class SiteComponent extends BaseComponent implements IPageCallStackAware
{
    use TPageCallStackAware;

    /**
     * Имя параметра маршрута для определения элемента структуры.
     */
    const MATCH_STRUCTURE_ELEMENT = 'element';

    /**
     * @var StructureApi $structureApi
     */
    protected $structureApi;

    /**
     * Возвращает URI страницы относительно компонента.
     * @param ICmsPage $page страница
     * @throws RuntimeException если невозможно получить URI
     */
    public function getPageUri(ICmsPage $page) {

        throw new RuntimeException(
            $this->translate(
                'Component "{path}" does not support URI generation.',
                ['path' => $this->getPath()]
            )
        );
    }

    /**
     * {@inheritdoc}
     * @param StructureApi $structureApi
     */
    public function __construct($name, $path, array $options = [], StructureApi $structureApi)
    {
        parent::__construct($name, $path, $options);

        $this->structureApi = $structureApi;
    }

    /**
     * {@inheritdoc}
     */
    public function onDispatchRequest(IDispatchContext $context, Request $request)
    {
        $element = null;
        if (isset($context->getRouteParams()[self::MATCH_STRUCTURE_ELEMENT])) {
            $element = $context->getRouteParams()[self::MATCH_STRUCTURE_ELEMENT];

            if ($element instanceof ICmsPage) {
                $this->pushCurrentPage($element);
            }
        }

        return null;
    }
}
 