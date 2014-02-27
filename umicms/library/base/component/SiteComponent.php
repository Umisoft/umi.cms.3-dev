<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\base\component;

use umi\hmvc\dispatcher\IDispatchContext;
use umi\http\Request;
use umicms\project\module\structure\api\StructureApi;
use umicms\project\module\structure\object\StructureElement;

/**
 * Компонент сайта.
 */
class SiteComponent extends BaseComponent
{
    /**
     * Имя параметра маршрута для определения элемента структуры.
     */
    const MATCH_ELEMENT = 'element';

    /**
     * @var StructureApi $structureApi
     */
    protected $structureApi;

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
        if (isset($context->getRouteParams()[self::MATCH_ELEMENT])) {
            $element = $context->getRouteParams()[self::MATCH_ELEMENT];

            if ($element instanceof StructureElement) {
                $this->structureApi->setCurrentElement($element);
            }
        }

        return null;
    }
}
 