<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\api;

use umi\orm\exception\IException;
use umicms\api\BaseCollectionApi;
use umicms\exception\NonexistentEntityException;
use umicms\project\module\news\object\NewsSubject;

/**
 * API для работы с новостными сюжетами.
 */
class NewsSubjectApi extends BaseCollectionApi
{
    /**
     * {@inheritdoc}
     */
    public $collectionName = 'news_subject';

    /**
     * Возвращает сюжет по его GUID
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить сюжет
     * @return NewsSubject
     */
    public function get($guid) {
        try {
            return $this->getCollection()->get($guid);
        } catch(IException $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find news item by guid "{guid}".',
                    ['guid' => $guid]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает сюжет по его последней части ЧПУ
     * @param string $slug последняя часть ЧПУ
     * @throws NonexistentEntityException если не удалось получить сюжет
     * @return NewsSubject
     */
    public function getBySlug($slug) {
        $selector = $this->select()
            ->where(NewsSubject::FIELD_SLUG)
            ->equals($slug);

        $subject = $selector->getResult()->fetch();

        if (!$subject instanceof NewsSubject) {
            throw new NonexistentEntityException($this->translate(
                'Cannot find news subject by slug "{slug}".',
                ['slug' => $slug]
            ));
        }

        return $subject;
    }
}
