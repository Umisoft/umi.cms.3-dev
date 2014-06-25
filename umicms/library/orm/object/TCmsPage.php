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
use umicms\orm\collection\CmsHierarchicCollection;
use umicms\orm\collection\CmsPageCollection;
use umicms\orm\collection\ICmsCollection;

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
     * Метод валидации slug.
     * @throws RuntimeException в случае, если объект не принадлежит CmsPageCollection или CmsHierarchicCollection
     * @return bool
     */
    public function validateSlug()
    {
        $result = true;

        /** @var CmsPageCollection|CmsHierarchicCollection $collection */
        $collection = $this->getCollection();

        if (!$collection instanceof CmsPageCollection || !$collection instanceof CmsHierarchicCollection) {
            throw new RuntimeException(
                $this->translate(
                    'Collection {collection} should be should be instance of CmsPageCollection or CmsHierarchicCollection.',
                    [
                        'collection' => $collection::className()
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
