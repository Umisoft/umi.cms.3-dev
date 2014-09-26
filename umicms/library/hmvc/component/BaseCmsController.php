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
use umi\orm\exception\RuntimeException;
use umi\orm\persister\IObjectPersister;
use umi\orm\persister\IObjectPersisterAware;
use umicms\exception\InvalidObjectsException;
use umicms\exception\RequiredDependencyException;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\hmvc\view\CmsView;
use umicms\module\IModuleAware;
use umicms\module\TModuleAware;
use umicms\orm\collection\behaviour\IRecoverableCollection;
use umicms\orm\object\behaviour\IRecoverableObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\project\module\search\model\SearchModule;
use umicms\project\module\users\model\UsersModule;

/**
 * Базовый контроллер UMI.CMS
 */
abstract class BaseCmsController extends BaseController
    implements IAclResource, IUrlManagerAware, ISwiftMailerAware, IObjectPersisterAware, IModuleAware
{
    use TUrlManagerAware;
    use TSwiftMailerAware;
    use TModuleAware;

    const ACL_RESOURCE_PREFIX = 'controller:';

    /**
     * @var IObjectPersister $traitObjectPersister синхронизатор объектов
     */
    private $objectPersister;

    /**
     * {@inheritdoc}
     */
    public function setObjectPersister(IObjectPersister $objectPersister)
    {
        $this->objectPersister = $objectPersister;
    }

    /**
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return self::ACL_RESOURCE_PREFIX . $this->name;
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
        $url = '';
        if ($isAbsolute) {
            $url .= $this->getUrlManager()->getSchemeAndHttpHost();
        }

        $url .= $this->getContext()->getBaseUrl();
        $url .= $this->getComponent()->getRouter()->assemble($routeName, $routeParams);

        if ($postfix = $this->getUrlManager()->getSiteUrlPostfix()) {
            $url .= '.' . $postfix;
        }

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    protected function createView($templateName, array $variables = [])
    {
        return new CmsView($this, $this->getContext(), $templateName, $variables);
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

    /**
     * Записывает изменения всех объектов в БД (бизнес транзакция),
     * запуская перед этим валидацию объектов.
     * Если при сохранении какого-либо объекта возникли ошибки - все изменения
     * автоматически откатываются
     * @throws InvalidObjectsException если объекты не прошли валидацию
     * @throws RuntimeException если транзакция не успешна
     * @return self
     */
    protected function commit()
    {
        /**
         * @var UsersModule $usersModule
         * @var SearchModule $searchModule
         */
        $usersModule = $this->getModuleByClass(UsersModule::className());
        $searchModule = $this->getModuleByClass(SearchModule::className());

        $currentUser = $usersModule->getCurrentUser();
        $searchIndexApi = $searchModule->getSearchIndexApi();

        $persister = $this->getObjectPersister();
        /**
         * @var ICmsObject|ICmsPage|IRecoverableObject $object
         */
        foreach ($persister->getModifiedObjects() as $object) {
            $collection = $object->getCollection();
            if ($collection instanceof IRecoverableCollection && $object instanceof IRecoverableObject) {
                $collection->createBackup($object);
            }
            if ($object instanceof ICmsPage) {
                $searchIndexApi->buildIndexForObject($object);
            }
        }
        foreach ($persister->getNewObjects() as $object) {
            if ($object instanceof ICmsPage) {
                $searchIndexApi->buildIndexForObject($object);
            }
        }
        foreach ($persister->getNewObjects() as $object) {
            $object->owner = $currentUser;
            $object->setCreatedTime();
        }
        foreach ($persister->getModifiedObjects() as $object) {
            $object->editor = $currentUser;
            $object->setUpdatedTime();
        }

        foreach ($persister->getDeletedObjects() as $object) {
            $deletedPages = [];
            if ($object instanceof ICmsPage) {
                $deletedPages[] = $object;
            }
            if ($deletedPages) {
                $searchIndexApi->deleteObjectIndexes($deletedPages);
            }
        }

        $invalidObjects = $persister->getInvalidObjects();

        if (count($invalidObjects)) {
            throw new InvalidObjectsException(
                $this->translate('Cannot persist objects. Objects are not valid.'),
                $invalidObjects
            );
        }

        $this->getObjectPersister()->commit();
    }

    /**
     * Возвращает синхронизатор объектов
     * @throws RequiredDependencyException если синхронизатор объектов не установлен
     * @return IObjectPersister
     */
    private function getObjectPersister()
    {
        if (!$this->objectPersister) {
            throw new RequiredDependencyException(sprintf(
                'Object persister is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->objectPersister;
    }

}
