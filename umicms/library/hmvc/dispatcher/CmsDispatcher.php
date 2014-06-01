<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\dispatcher;

use umi\acl\IAclManager;
use umi\hmvc\acl\ComponentRoleProvider;
use umi\hmvc\acl\IComponentRoleResolver;
use umi\hmvc\component\IComponent;
use umi\hmvc\dispatcher\Dispatcher;
use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umi\hmvc\view\IView;
use umicms\authentication\CmsAuthStorage;
use umicms\exception\InvalidArgumentException;
use umicms\exception\UnexpectedValueException;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\hmvc\widget\BaseAccessRestrictedWidget;
use umicms\project\module\users\api\object\Supervisor;

/**
 * {@inheritdoc}
 */
class CmsDispatcher extends Dispatcher implements IUrlManagerAware
{
    use TUrlManagerAware;

    /**
     * Начальный путь компонентов сайта
     */
    const SITE_COMPONENT_PATH = 'project.site';
    /**
     * Начальный путь административных компонентов
     */
    const ADMIN_COMPONENT_PATH = 'project.admin';
    /**
     * Начальный путь административных api компонентов
     */
    const ADMIN_API_COMPONENT_PATH = 'project.admin.api';
    /**
     * Начальный путь административных настроечных компонентов
     */
    const ADMIN_SETTINGS_COMPONENT_PATH = 'project.admin.settings';

    /**
     * Обрабатывает вызов виджета.
     * @param string $widgetPath путь виджета
     * @param array $params параметры вызова виджета
     * @throws InvalidArgumentException при неверном вызове виджета
     * @return string|IView
     */
    public function executeWidgetByPath($widgetPath, array $params = [])
    {
        $widgetPathParts = explode(IComponent::PATH_SEPARATOR, $widgetPath);
        if (count($widgetPathParts) < 2) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Cannot resolve widget path "{path}".',
                    ['path' => $widgetPath]
                )
            );
        }

        $widgetName = array_pop($widgetPathParts);
        $componentPageUrl = $this->getUrlManager()->getSystemPageUrl(implode(IComponent::PATH_SEPARATOR, $widgetPathParts));

        $projectUrl = $this->getUrlManager()->getProjectUrl();
        if ($projectUrl != '/') {
            $componentPageUrl = substr($componentPageUrl, strlen($projectUrl));
        }

        return $this->executeWidget($componentPageUrl . '/' . $widgetName, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function executeWidget($widgetUri, array $params = [])
    {
        list ($component, $callStack, $componentURI) = $this->resolveWidgetContext($widgetUri);

        try {

            try {
                $widget = $this->dispatchWidget($component, $widgetUri, $params, $callStack, $componentURI);

                return $this->invokeWidget($widget);
            } catch (ResourceAccessForbiddenException $e) {

                $resource = $e->getResource();
                if ($resource instanceof BaseAccessRestrictedWidget) {
                    return $this->invokeWidgetForbidden($resource, $e);
                }

                throw $e;
            }

        } catch (\Exception $e) {

            $context = $this->createDispatchContext($component);
            $context->setCallStack(clone $callStack);

            return $this->processWidgetError($e, $context);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkPermissions(IComponent $component, $resource, $operationName = IAclManager::OPERATION_ALL)
    {
        $authManager = $this->getDefaultAuthManager();
        /**
         * @var CmsAuthStorage $storage
         */
        $storage = $authManager->getStorage();

        if ($authManager->isAuthenticated()) {
            $identity = $storage->getIdentity();

            if ($identity instanceof Supervisor) {
                return true;
            }

        } else {
            $identity = $storage->getGuestIdentity();
        }

        if (!$identity instanceof IComponentRoleResolver) {
            return false;
        }
        $roleProvider = new ComponentRoleProvider($component, $identity);

        $aclManager = $component->getAclManager();

        return $aclManager->isAllowed($roleProvider, $resource, $operationName);
    }

    /**
     * Вызывает обработку результата в случае отсутствия доступа к виджету.
     * @param BaseAccessRestrictedWidget $widget
     * @param ResourceAccessForbiddenException $e
     * @throws UnexpectedValueException если виджет вернул неверный результат
     * @return IView|string
     */
    protected function invokeWidgetForbidden(BaseAccessRestrictedWidget $widget, ResourceAccessForbiddenException $e)
    {
        $widgetResult = $widget->invokeForbidden($e);

        if (!$widgetResult instanceof IView && !is_string($widgetResult)) {
            throw new UnexpectedValueException($this->translate(
                'Widget "{widget}" returns unexpected value. String or instance of IView expected.',
                ['widget' => get_class($widget)]
            ));
        }

        return $widgetResult;
    }

}
 