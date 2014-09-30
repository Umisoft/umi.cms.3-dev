<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\object;

use umi\orm\metadata\field\special\DelayedField;
use umi\orm\object\property\IProperty;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\serialization\ISerializer;
use umicms\serialization\TSerializerConfigurator;
use umicms\serialization\xml\BaseSerializer;

/**
 * Трейт для поддержки объектов.
 */
trait TCmsObject
{
    use TUrlManagerAware;
    use TSerializerConfigurator;

    /**
     * @var string $traitEditLink ссылка на редактирование объекта
     */
    private $traitEditLink;

    /**
     * Возвращает коллекцию, к которой принадлежит объект
     * @return ICmsCollection
     */
    abstract public function getCollection();
    /**
     * @see ICmsObject::getIsModified()
     */
    abstract public function getIsModified();
    /**
     * @see ICmsObject::getIsNew()
     */
    abstract public function getIsNew();
    /**
     * @see ICmsObject::getAllProperties()
     * @return IProperty[]
     */
    abstract public function getAllProperties();
    /**
     * @see ICmsObject::getModifiedProperties()
     * @return IProperty[]
     */
    abstract public function getModifiedProperties();
    /**
     * @see ICmsObject::getProperty()
     * @param string $propName
     * @param null|string $localeId
     * @return IProperty
     */
    abstract public function getProperty($propName, $localeId = null);

    /**
     * @see ICmsObject::setValue()
     * @param string $propName
     * @param mixed $value
     * @param null|string $localeId
     * @return self
     */
    abstract public function setValue($propName, $value, $localeId = null);
    /**
     * @see ICmsObject::getValue()
     */
    abstract public function getValue($propName, $localeId = null);

    /**
     * @see CmsObject::getLocalizedValue()
     */
    abstract protected function getLocalizedValue(IProperty $property, $localeId);

    /**
     * @see TLocalesAware::getDefaultDataLocale()
     */
    abstract protected function getDefaultDataLocale();
    /**
     * @see TLocalesAware::getCurrentDataLocale()
     */
    abstract protected function getCurrentDataLocale();

    /**
     * Возаращает имя класса объекта.
     * @return string
     */
    public static function className() {
        return get_called_class();
    }

    /**
     * @see ISerializerConfigurator::configureSerializer()
     */
    public function configureSerializer(ISerializer $serializer)
    {
        $this->addSerializerConfigurator(
            function(ISerializer $serializer) {
                if ($serializer instanceof BaseSerializer) {
                    $attributes = array_keys($this->getCollection()->getForcedFieldsToLoad());
                    $attributes[] = ICmsObject::FIELD_DISPLAY_NAME;
                    $serializer->setAttributes($attributes);
                }
                $serializer->setExcludes(['uri']);
            }
        );

        foreach ($this->configurators as $configurator) {
            $configurator($serializer);
        }
    }

    /**
     * @see ICmsObject::getEditLink()
     */
    public function getEditLink($isAbsolute = false)
    {
        if (!$this->traitEditLink) {
            /** @noinspection PhpParamsInspection */
            $this->traitEditLink = $this->getUrlManager()->getObjectEditLinkUrl($this, $isAbsolute);
        }

        return $this->traitEditLink;
    }

    /**
     * @see ICmsObject::validate()
     */
    public function validate()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        $this->validationErrors = [];

        if (!$this->getIsModified() && !$this->getIsNew()) {
            return true;
        }

        $this->fillProperties();
        $this->fillLocalizedProperties();

        $result = true;

        foreach ($this->getAllProperties() as $property) {
            $localeId = $property->getLocaleId();
            if ($localeId && $localeId !== $this->getCurrentDataLocale()) {
                continue;
            }

            if ($property->getField()->getIsLocalized()) {
                $value = $this->getLocalizedValue($property, $localeId);
            } else {
                $value = $property->getValue();
            }

            if (!$property->validate($value)) {
                /** @noinspection PhpUndefinedFieldInspection */
                $this->validationErrors[$property->getFullName()] = $property->getValidationErrors();
                $result = false;
            }
        }

        return $result;
    }

    /**
     * @see ICmsObject::setCreatedTime()
     */
    public function setCreatedTime()
    {
        $property = $this->getProperty('created');
        $property->setValue(new \DateTime());

        return $this;
    }

    /**
     * @see ICmsObject::setUpdatedTime()
     */
    public function setUpdatedTime()
    {
        $property = $this->getProperty('updated');
        $property->setValue(new \DateTime());

        return $this;
    }

    /**
     * @see IAclResource::getAclResourceName()
     */
    public function getAclResourceName()
    {
        /** @var $this ICmsObject */
        return "model:{$this->getTypePath()}";
    }

    /**
     * @see IAclAssertionResolver::isAllowed()
     */
    public function isAllowed($role, $operationName, array $assertions)
    {
        return true;
    }

    /**
     * Заполняет пустые значения свойств.
     */
    protected function fillProperties()
    {

    }

    /**
     * @see TLocalizable::getI18nDictionaryNames()
     */
    protected function getI18nDictionaryNames()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @noinspection PhpUndefinedClassInspection */
        return array_merge(parent::getI18nDictionaryNames(), $this->getCollection()->getDictionaryNames());
    }

    /**
     * Заполняет пустые значения дефолтной локали значениями из текущей.
     */
    private function fillLocalizedProperties()
    {
        $currentLocaleId = $this->getCurrentDataLocale();
        $defaultLocaleId = $this->getDefaultDataLocale();

        if ($currentLocaleId !== $defaultLocaleId) {
            foreach ($this->getModifiedProperties() as $property) {
                if ($property->getField() instanceof DelayedField) {
                    continue;
                }
                if ($property->getLocaleId()) {
                    $name = $property->getName();
                    if ($name === IActiveAccessibleObject::FIELD_ACTIVE) {
                        continue;
                    }
                    if ($this->getValue($name, $defaultLocaleId)) {
                        continue;
                    }
                    $this->setValue($name, $this->getValue($name), $defaultLocaleId);
                }
            }
        }
    }

}
 