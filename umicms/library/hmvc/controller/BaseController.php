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
use umi\messages\ISwiftMailerAware;
use umi\messages\TSwiftMailerAware;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\hmvc\view\CmsView;

/**
 * Базовый контроллер UMI.CMS
 */
abstract class BaseController extends FrameworkController implements IUrlManagerAware, ISwiftMailerAware
{
    use TUrlManagerAware;
    use TSwiftMailerAware;

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

    /**
     * Отправляет письмо.
     * @param string|array $to адресат
     * @param string|array $from отправитель
     * @param string $subjectTemplate имя шаблона темы письма
     * @param string $bodyTemplate имя шаблона содержимого письма
     * @param array $variables
     */
    protected function mail($to, $from, $subjectTemplate, $bodyTemplate, $variables = [])
    {
        $variables['projectUrl'] = $this->getUrlManager()->getProjectUrl(true);

        $body = (string) $this->createView(
            $bodyTemplate,
            $variables
        );

        $subject = (string) $this->createView(
            $subjectTemplate,
            $variables
        );

        $this->sendMail($subject, $body, 'text/html', [], $to, $from);
    }

}
