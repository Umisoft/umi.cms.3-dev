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

use umi\orm\metadata\field\IRelationField;
use umi\orm\metadata\field\relation\BelongsToRelationField;
use umi\orm\metadata\field\special\IdentifyField;
use umi\orm\object\property\IProperty;
use umicms\hmvc\url\TUrlManagerAware;

/**
 * Трейт для поддержки объектов.
 */
trait TCmsObject
{
    use TUrlManagerAware;

    /**
     * @var string $traitEditLink ссылка на редактирование объекта
     */
    private $traitEditLink;

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
     * @see ICmsObject::getProperty()
     * @param string $propName
     * @param null|string $localeId
     * @return IProperty
     */
    abstract public function getProperty($propName, $localeId = null);

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

        $result = true;

        foreach ($this->getAllProperties() as $property) {

            $localeId = $property->getLocaleId();
            if ($localeId && $localeId !== $this->getDefaultDataLocale() && $localeId !== $this->getCurrentDataLocale()) {
                continue;
            }

            if (!$property->validate()) {
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

}
 