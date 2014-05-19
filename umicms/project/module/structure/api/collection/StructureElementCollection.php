<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\api\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umi\orm\object\IHierarchicObject;
use umicms\exception\NonexistentEntityException;
use umicms\exception\NotAllowedOperationException;
use umicms\orm\collection\behaviour\ILockedAccessibleCollection;
use umicms\orm\collection\behaviour\TLockedAccessibleCollection;
use umicms\orm\collection\PageHierarchicCollection;
use umicms\orm\object\behaviour\ILockedAccessibleObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\structure\api\object\StructureElement;
use umicms\project\module\structure\api\object\SystemPage;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * Коллекция для работы с элементами структуры сайта.
 *
 * @method CmsSelector|StructureElement[] select() Возвращает селектор для выбора элементов структуры.
 * @method StructureElement get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает элемент по GUID.
 * @method StructureElement getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает элемент по id.
 * @method StructureElement getByUri($uri, $localization = ILocalesService::LOCALE_CURRENT) Возвращает элемент по URI.
 * @method StructureElement add($slug, $typeName = IObjectType::BASE, IHierarchicObject $branch = null) Добавляет элемент.
 */
class StructureElementCollection extends PageHierarchicCollection implements ILockedAccessibleCollection, ISiteSettingsAware
{
    use TLockedAccessibleCollection;
    use TSiteSettingsAware;

    /**
     * Возвращает страницу сайта по умолчанию.
     * @return StructureElement
     */
    public function getDefaultPage()
    {
        return $this->get($this->getSiteDefaultPageGuid());
    }

    /**
     * {@inheritdoc}
     */
    public function move(IHierarchicObject $object, IHierarchicObject $branch = null, IHierarchicObject $previousSibling = null) {

        /**
         * @var ILockedAccessibleObject|IHierarchicObject $object
         */
        if ($object instanceof ILockedAccessibleObject && $object->locked && $branch !== $object->getParent()) {
            throw new NotAllowedOperationException('Cannot move locked page.');
        }

        return parent::move($object, $branch, $previousSibling);
    }

    /**
     * Возвращает системную страницу по пути ее компонента-обработчика
     * @param string $componentPath путь ее компонента-обработчика
     * @throws NonexistentEntityException если такой страницы нет
     * @return SystemPage
     */
    public function getSystemPageByComponentPath($componentPath)
    {
        $page = $this->selectSystem()
            ->where(SystemPage::FIELD_COMPONENT_PATH)
            ->equals($componentPath)
            ->limit(1)
            ->getResult()
            ->fetch();

        if (!$page instanceof SystemPage) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find element by component path "{path}".',
                    ['path' => $componentPath]
                )
            );
        }

        return $page;
    }

    /**
     * Возвращает селектор для выбора системных страниц.
     * @return CmsSelector|SystemPage[]
     */
    public function selectSystem()
    {
        return $this->select()->types([SystemPage::TYPE]);
    }
}
