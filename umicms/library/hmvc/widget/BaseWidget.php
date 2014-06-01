<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\widget;

use umi\hmvc\component\IComponent;
use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umi\hmvc\view\IView;
use umi\hmvc\widget\BaseWidget as FrameworkWidget;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\hmvc\view\CmsTreeView;
use umicms\hmvc\view\CmsView;
use umicms\orm\selector\CmsSelector;
use umicms\project\site\callstack\IPageCallStackAware;
use umicms\project\site\callstack\TPageCallStackAware;

/**
 * Базовый виджет UMI.CMS
 */
abstract class BaseWidget extends FrameworkWidget implements IUrlManagerAware, IPageCallStackAware
{
    use TUrlManagerAware;
    use TPageCallStackAware;

    const ACL_RESOURCE_PREFIX = 'widget:';

    /**
     * @var string $forbiddenTemplate имя шаблона, по которому выводится виджет в случае отсутствия доступа к нему.
     */
    public $forbiddenTemplate = 'widget.forbidden';

    /**
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return self::ACL_RESOURCE_PREFIX . $this->name;
    }

    /**
     * Формирует результат в случае отсутствия доступа к виджету.
     * @param ResourceAccessForbiddenException $e
     * @return CmsView
     */
    public function invokeForbidden(ResourceAccessForbiddenException $e)
    {
        return $this->createResult($this->forbiddenTemplate, ['error' => $e]);
    }

    /**
     * Устанавливает опции сериализации результата работы виджета в XML или JSON.
     * Может быть переопределен в конкретном виджете для задания переменных,
     * которые будут преобразованы в атрибуты xml, а так же переменные, которые будут проигнорированы
     * в xml или json.
     * @param CmsView $view результат работы виджета
     */
    protected function setSerializationOptions(CmsView $view)
    {

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
    protected function createResult($templateName, array $variables = [])
    {
        $variables['widget'] = $this->getShortPath();
        $view = new CmsView($this, $this->getContext(), $templateName, $variables);

        $view->setXmlAttributes(['widget']);
        $this->setSerializationOptions($view);

        return $view;
    }

    /**
     * Создает результат работы виджета, требующий шаблонизации.
     * @param string $templateName имя шаблона
     * @param CmsSelector $selector
     * @return IView
     */
    protected function createTreeResult($templateName, CmsSelector $selector)
    {
        $view = new CmsTreeView($selector);
        $view->setPageCallStack($this->getPageCallStack());

        return $this->createResult($templateName, [
            'tree' => $view
        ]);
    }

    /**
     * Возвращает короткий путь виджета, относительно приложения сайта
     * @return string
     */
    private function getShortPath()
    {
        $relativePath = substr($this->getComponent()->getPath(), strlen(CmsDispatcher::SITE_COMPONENT_PATH) + 1);
        $relativePath .= IComponent::PATH_SEPARATOR . $this->getName();

        return $relativePath;
    }
}
 