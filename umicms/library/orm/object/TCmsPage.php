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

use umi\orm\object\property\IProperty;
use umicms\exception\RuntimeException;
use umicms\hmvc\url\IUrlManager;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\collection\ICmsPageCollection;

/**
 * Трейт для поддержки страниц.
 */
trait TCmsPage
{
    /**
     * @see IObject::getCollection()
     * @return ICmsCollection
     */
    abstract public function getCollection();

    /**
     * @see IObject::getProperty()
     * @param string $propName
     * @param string $localeId
     * @return IProperty
     */
    abstract public function getProperty($propName, $localeId = null);

    /**
     * @see TUrlManagerAware::getUrlManager()
     * @return IUrlManager
     */
    abstract protected function getUrlManager();

    /**
     * @see TLocalizable::translate()
     */
    abstract public function translate($message, array $placeholders = [], $localeId = null);

    /**
     * @see ICmsPage::getPageUrl()
     */
    public function getPageUrl($isAbsolute = false)
    {
        /** @noinspection PhpParamsInspection */
        return $this->getUrlManager()->getSitePageUrl($this, $isAbsolute);
    }

    /**
     * @see ICmsPage::getHeader()
     */
    public function getHeader()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return $this->h1 ? $this->h1 : $this->displayName;
    }

    /**
     * Метод валидации slug.
     * @throws RuntimeException в случае неверной коллекции объекта
     * @return bool
     */
    public function validateSlug()
    {
        $result = true;

        /** @var ICmsPageCollection $collection */
        $collection = $this->getCollection();

        if (!$collection instanceof ICmsPageCollection) {
            throw new RuntimeException(
                $this->translate(
                    'Collection {collection} should be instance of umicms\orm\collection\ICmsPageCollection.',
                    [
                        'collection' => $collection->getName()
                    ]
                )
            );
        }

        if (!$collection->isAllowedSlug($this)) {
            $result = false;
            $this->getProperty(ICmsPage::FIELD_PAGE_SLUG)->addValidationErrors(
                [$this->translate('Slug is not unique.')]
            );
        }

        return $result;
    }
}
 