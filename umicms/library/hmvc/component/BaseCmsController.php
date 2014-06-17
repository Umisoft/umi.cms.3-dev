<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component;

use umi\acl\IAclResource;
use umi\hmvc\controller\BaseController;
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
abstract class BaseCmsController extends BaseController implements IAclResource, IUrlManagerAware, ISwiftMailerAware
{
    use TUrlManagerAware;
    use TSwiftMailerAware;

    const ACL_RESOURCE_PREFIX = 'controller:';

    /**
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return self::ACL_RESOURCE_PREFIX . $this->name;
    }

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
