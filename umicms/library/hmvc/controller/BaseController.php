<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\controller;

use umi\hmvc\controller\BaseController as FrameworkController;
use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\hmvc\view\CmsView;

/**
 * Базовый контроллер UMI.CMS
 */
abstract class BaseController extends FrameworkController implements IUrlManagerAware
{
    use TUrlManagerAware;

    /**
     * Устанавливает опции сериализации результата работы контроллера в XML или JSON.
     * Может быть переопределен в конкретном контроллере для задания переменных,
     * которые будут преобразованы в атрибуты xml, а так же переменные, которые будут проигнорированы
     * в xml или json.
     * @param CmsView $view результат работы виджета
     */
    protected function setSerializationOptions(CmsView $view)
    {

    }

    /**
     * Возвращает значение параметра из GET-параметров запроса.
     * @param string $name имя параметра
     * @throws HttpException если значение не найдено
     * @return mixed
     */
    protected function getRequiredQueryVar($name)
    {
        $value = $this->getQueryVar($name);
        if (is_null($value)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST,
                $this->translate(
                    'Query parameter "{param}" required.',
                    ['param' => $name]
                )
            );
        }

        return $value;
    }

    /**
     * Возвращает URL маршрута компонента.
     * @param string $routeName
     * @param array $routeParams параметры маршрута
     * @param bool $isAbsolute возвращать ли абсолютный URL
     * @return string
     */
    protected function getUrl($routeName, array $routeParams = [], $isAbsolute = false)
    {
        $url = rtrim($this->getUrlManager()->getProjectUrl($isAbsolute), '/');
        $url .= $this->getContext()->getBaseUrl();
        $url .= $this->getComponent()->getRouter()->assemble($routeName, $routeParams);

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    protected function createView($templateName, array $variables = [])
    {
        $view = new CmsView($this, $this->getContext(), $templateName, $variables);

        $this->setSerializationOptions($view);

        return $view;
    }

}
