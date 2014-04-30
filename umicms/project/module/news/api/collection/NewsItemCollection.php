<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\api\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\exception\NonexistentEntityException;
use umicms\orm\collection\PageCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\news\api\object\NewsItem;
use umicms\project\module\news\api\object\NewsRubric;
use umicms\project\module\news\api\object\NewsSubject;

/**
 * Коллекция для работы с новостями.
 *
 * @method CmsSelector|NewsItem[] select() Возвращает селектор для выбора новостей.
 * @method NewsItem get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает новость по ее GUID.
 * @method NewsItem getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает новость по ее id.
 * @method NewsItem add($typeName = IObjectType::BASE) Создает и возвращает новость.
 */
class NewsItemCollection extends PageCollection
{

    /**
     * Возвращает селектор для выбора новостей рубрики.
     * @param NewsRubric $rubric рубрика
     * @return CmsSelector|NewsItem[]
     */
    public function getNewsByRubric(NewsRubric $rubric)
    {
        return $this->select()
            ->where(NewsItem::FIELD_RUBRIC)
            ->equals($rubric);
    }

    /**
     * Возвращает селектор для выбора новостей сюжета.
     * @param NewsSubject $subject
     * @return CmsSelector|NewsItem[]
     */
    public function getNewsBySubject(NewsSubject $subject)
    {
        return $this->select()
            ->where(NewsItem::FIELD_SUBJECTS)
            ->equals($subject);
    }

    /**
     * Возвращает новость по её источнику.
     * @param string $source
     * @throws NonexistentEntityException если новость не существует
     * @return NewsItem
     */
    public function getNewsBySource($source)
    {
        $selector = $this->select()
            ->where(NewsItem::FIELD_SOURCE)
            ->equals($source);

        $item = $selector->getResult()->fetch();

        if (!$item instanceof NewsItem) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find news item by source "{source}".',
                    ['source' => $source]
                )
            );
        }

        return $item;
    }

}