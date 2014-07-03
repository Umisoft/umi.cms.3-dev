<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\collection;

use umi\form\TFormAware;
use umi\i18n\TLocalesAware;
use umi\i18n\TLocalizable;
use umi\orm\collection\TCollectionManagerAware;
use umi\orm\metadata\IMetadata;
use umi\orm\metadata\IObjectType;
use umi\orm\object\IObject;
use umi\spl\config\TConfigSupport;
use umicms\exception\NonexistentEntityException;
use umicms\exception\NotAllowedOperationException;
use umicms\exception\OutOfBoundsException;
use umicms\orm\object\behaviour\ILockedAccessibleObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\selector\CmsSelector;

/**
 * Трейт коллекции объектов UMI.CMS
 */
trait TCmsCollection
{
    use TCollectionManagerAware;
    use TFormAware;
    use TConfigSupport;
    use TLocalizable;
    use TLocalesAware;

    /**
     * @see ICmsCollection::getName()
     */
    abstract public function getName();
    /**
     * @see ICmsCollection::getMetadata()
     * @return IMetadata
     */
    abstract public function getMetadata();

    /**
     * Возаращает имя класса коллекции.
     * @return string
     */
    public static function className() {
        return get_called_class();
    }

    /**
     * Возвращает новый селектор для формирования выборки объектов коллекции.
     * @return CmsSelector
     */
    public function select()
    {
        /**
         * @var CmsSelector $selector
         */
        /** @noinspection PhpUndefinedMethodInspection */
        /** @noinspection PhpUndefinedClassInspection */
        $selector = parent::select();

        /** @noinspection PhpUndefinedFieldInspection */
        if ($initializer = self::$selectorInitializer) {
            $initializer($selector);
        }

        return $selector;
    }

    /**
     * @see ICmsCollection::delete()
     */
    public function delete(IObject $object)
    {
        if ($object instanceof ILockedAccessibleObject && $object->locked) {
            throw new NotAllowedOperationException(
                $this->translate(
                    'Cannot delete locked object with GUID "{guid}" from collection "{collection}".',
                    ['guid' => $object->guid, 'collection' => $object->getCollectionName()]
                )
            );
        }

        /** @noinspection PhpUndefinedMethodInspection */
        /** @noinspection PhpUndefinedClassInspection */
        return parent::delete($object);
    }

    /**
     * @see ICmsCollection::getType()
     */
    public function getType()
    {
        return $this->traitGetConfig()['type'];
    }

    /**
     * @see ICmsCollection::getForm()
     */
    public function getForm($formName, $typeName = IObjectType::BASE, ICmsObject $object = null)
    {
        if (!$this->hasForm($formName, $typeName)) {
            throw new NonexistentEntityException(
                sprintf(
                    'Form "%s" for collection "%s" and type "%s" is not registered.',
                    $formName,
                    $this->getName(),
                    $typeName
                )
            );
        }

        $formConfig = $this->configToArray($this->traitGetConfig()['forms'][$typeName][$formName], true);

        return $this->createForm($formConfig, $object);
    }

    /**
     * @see ICmsCollection::hasForm()
     */
    public function hasForm($formName, $typeName = IObjectType::BASE)
    {
        if (!$this->getMetadata()->getTypeExists($typeName)) {
            return false;
        }

        if (!isset($this->traitGetConfig()['forms'][$typeName][$formName])) {
            return false;
        }

        return true;
    }


    /**
     * @see ICmsCollection::getHandlerPath()
     */
    public function getHandlerPath($applicationName)
    {
        if (!$this->hasHandler($applicationName)) {
            throw new OutOfBoundsException(
                sprintf(
                    'Handler for collection "%s" and application "%s" is unknown.',
                    $this->getName(),
                    $applicationName
                )
            );
        }

        return $this->traitGetConfig()['handlers'][$applicationName];
    }

    /**
     * @see ICmsCollection::getHandlerList()
     */
    public function getHandlerList()
    {
        return (isset($this->traitGetConfig()['handlers'])) ? $this->traitGetConfig()['handlers'] : [];
    }

    /**
     * @see ICmsCollection::hasHandler()
     */
    public function hasHandler($applicationName)
    {
        return isset($this->traitGetConfig()['handlers'][$applicationName]);
    }

    /**
     * @see ICmsCollection::getDictionaryNames()
     */
    public function getDictionaryNames()
    {
        $dictionaries = isset($this->traitGetConfig()['dictionaries']) ? $this->traitGetConfig()['dictionaries'] : [];
        $dictionaries = $this->configToArray($dictionaries);

        return $dictionaries;
    }

    /**
     * @see ICmsCollection::getCreateTypeList()
     */
    public function getCreateTypeList()
    {
        $result = [];

        if ($this->getCurrentDataLocale() != $this->getDefaultDataLocale()) {
            return $result;
        }

        foreach ($this->getMetadata()->getTypesList() as $typeName) {
            if ($this->hasForm(ICmsCollection::FORM_CREATE, $typeName)) {
                $result[] = $typeName;
            }
        }

        return $result;
    }

    /**
     * @see ICmsCollection::getEditTypeList()
     */
    public function getEditTypeList()
    {
        $result = [];

        foreach ($this->getMetadata()->getTypesList() as $typeName) {
            if ($this->hasForm(ICmsCollection::FORM_EDIT, $typeName)) {
                $result[] = $typeName;
            }
        }

        return $result;
    }

    /**
     * @see ICmsCollection::getDefaultTableFilterFieldNames()
     */
    public function getDefaultTableFilterFieldNames()
    {
        $defaultFieldNames = [
            ICmsObject::FIELD_DISPLAY_NAME
        ];
        if ($this instanceof ICmsPageCollection) {
            $defaultFieldNames[] = ICmsPage::FIELD_PAGE_H1;
            $defaultFieldNames[] = ICmsPage::FIELD_PAGE_LAYOUT . '.' . ICmsObject::FIELD_DISPLAY_NAME;
            $defaultFieldNames[] = ICmsPage::FIELD_PAGE_SLUG;
        }

        $fieldNames = isset($this->traitGetConfig()[ICmsCollection::DEFAULT_TABLE_FILTER_FIELDS]) ?
            $this->traitGetConfig()[ICmsCollection::DEFAULT_TABLE_FILTER_FIELDS] : [];

        return array_merge($defaultFieldNames, array_keys($fieldNames));
    }

    /**
     * @see ICmsCollection::getIgnoredTableFilterFieldNames()
     */
    public function getIgnoredTableFilterFieldNames()
    {
        $defaultIgnoredFieldNames = [
            ICmsObject::FIELD_VERSION,
            CmsHierarchicObject::FIELD_MPATH,
            CmsHierarchicObject::FIELD_URI,
            IRecyclableObject::FIELD_TRASHED,
            ILockedAccessibleObject::FIELD_LOCKED,
            ICmsPage::FIELD_PAGE_CONTENTS
        ];

        $fieldNames = isset($this->traitGetConfig()[ICmsCollection::IGNORED_TABLE_FILTER_FIELDS]) ?
            $this->traitGetConfig()[ICmsCollection::IGNORED_TABLE_FILTER_FIELDS] : [];

        return array_merge($defaultIgnoredFieldNames, array_keys($fieldNames));
    }

    /**
     * Возвращает значение настройки для коллекции.
     * @param string $settingName имя настройки
     * @param mixed $defaultValue значение по умолчанию
     * @return mixed
     */
    protected function getSetting($settingName, $defaultValue = null) {
        if (isset($this->traitGetConfig()['settings'][$settingName])) {
            return $this->traitGetConfig()['settings'][$settingName];
        }

        return $defaultValue;
    }

    /**
     * Возвращает новый селектор для формирования выборки объектов коллекции без учета установленных инициализаторов.
     * @return CmsSelector
     */
    protected function selectInternal() {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @noinspection PhpUndefinedClassInspection */
        return parent::select();
    }

    /**
     * Возвращает конфигурацию коллекции.
     * @return array
     */
    private function traitGetConfig()
    {
       return isset($this->config) ? $this->config : [];
    }
}
 