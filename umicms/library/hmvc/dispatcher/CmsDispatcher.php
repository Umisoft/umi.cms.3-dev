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
use umicms\exception\InvalidArgumentException;
use umicms\exception\UnexpectedValueException;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\module\IModuleAware;
use umicms\module\TModuleAware;
use umicms\project\module\users\model\object\Supervisor;
use umicms\project\module\users\model\UsersModule;

/**
 * {@inheritdoc}
 */
class CmsDispatcher extends Dispatcher implements IUrlManagerAware, IModuleAware
{
    use TUrlManagerAware;
    use TModuleAware;

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
    const ADMIN_API_COMPONENT_PATH = 'project.admin.rest';
    /**
     * Начальный путь административных настроечных компонентов
     */
    const ADMIN_SETTINGS_COMPONENT_PATH = 'project.admin.settings';

    /**
     * @var array $widgetCallCounter счетчик вызова виджетов
     */
    protected $widgetCallCounter = [];

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
        if (count($widgetPathParts) > 1) {
            $widgetName = array_pop($widgetPathParts);
            $componentPageUrl = $this->getUrlManager()->getRawSystemPageUrl(implode(IComponent::PATH_SEPARATOR, $widgetPathParts));
            $widgetUri = '/' . $componentPageUrl . '/' . $widgetName;
        } else {
            $widgetUri = '/' . $widgetPath;
        }

        return $this->executeWidget($widgetUri, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function executeWidget($widgetUri, array $params = [])
    {
        list ($component, $callStack, $componentURI) = $this->resolveWidgetContext($widgetUri);

        try {

            try {
                /**
                 * @var BaseCmsWidget $widget
                 */
                $widget = $this->dispatchWidget($component, $widgetUri, $params, $callStack, $componentURI);

                if (!isset($this->widgetCallCounter[$widgetUri])) {
                    $this->widgetCallCounter[$widgetUri] = 0;
                } else {
                    $this->widgetCallCounter[$widgetUri]++;
                }

                $widget->setCallCounter($this->widgetCallCounter[$widgetUri]);

                return $this->invokeWidget($widget);
            } catch (ResourceAccessForbiddenException $e) {
                $resource = $e->getResource();
                if ($resource instanceof BaseCmsWidget) {
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

        /**
         * @var UsersModule $usersModule
         */
        $usersModule = $this->getModuleByClass(UsersModule::className());
        $currentUser = $usersModule->getCurrentUser();

        if ($currentUser instanceof Supervisor) {
            return true;
        }

        if (!$currentUser instanceof IComponentRoleResolver) {
            return false;
        }
        $roleProvider = new ComponentRoleProvider($component, $currentUser);

        $aclManager = $component->getAclManager();

        return $aclManager->isAllowed($roleProvider, $resource, $operationName);
    }

    /**
     * Вызывает обработку результата в случае отсутствия доступа к виджету.
     * @param BaseCmsWidget $widget
     * @param ResourceAccessForbiddenException $e
     * @throws UnexpectedValueException если виджет вернул неверный результат
     * @return IView|string
     */
    protected function invokeWidgetForbidden(BaseCmsWidget $widget, ResourceAccessForbiddenException $e)
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
 