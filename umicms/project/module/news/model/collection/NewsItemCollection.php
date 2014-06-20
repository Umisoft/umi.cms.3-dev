<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\exception\NonexistentEntityException;
use umicms\orm\collection\CmsPageCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\news\model\object\NewsItem;
use umicms\project\module\news\model\object\NewsRubric;
use umicms\project\module\news\model\object\NewsSubject;

/**
 * Коллекция для работы с новостями.
 *
 * @method CmsSelector|NewsItem[] select() Возвращает селектор для выбора новостей.
 * @method NewsItem get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает новость по ее GUID.
 * @method NewsItem getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает новость по ее id.
 * @method NewsItem add($typeName = IObjectType::BASE) Создает и возвращает новость.
 */
class NewsItemCollection extends CmsPageCollection
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