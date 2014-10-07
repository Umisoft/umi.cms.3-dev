<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umi\orm\object\IHierarchicObject;
use umicms\exception\NonexistentEntityException;
use umicms\exception\NotAllowedOperationException;
use umicms\orm\collection\behaviour\ILockedAccessibleCollection;
use umicms\orm\collection\behaviour\TLockedAccessibleCollection;
use umicms\orm\collection\CmsHierarchicPageCollection;
use umicms\orm\object\behaviour\ILockedAccessibleObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\structure\model\object\StructureElement;
use umicms\project\module\structure\model\object\SystemPage;
use umicms\project\IProjectSettingsAware;
use umicms\project\TProjectSettingsAware;

/**
 * Коллекция для работы с элементами структуры сайта.
 *
 * @method CmsSelector|StructureElement[] select() Возвращает селектор для выбора элементов структуры.
 * @method StructureElement get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает элемент по GUID.
 * @method StructureElement getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает элемент по id.
 * @method StructureElement getByUri($uri, $localization = ILocalesService::LOCALE_CURRENT) Возвращает элемент по URI.
 * @method StructureElement add($slug = null, $typeName = IObjectType::BASE, IHierarchicObject $branch = null, $guid = null) Добавляет элемент.
 */
class StructureElementCollection extends CmsHierarchicPageCollection implements ILockedAccessibleCollection, IProjectSettingsAware
{
    use TLockedAccessibleCollection;
    use TProjectSettingsAware;

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
