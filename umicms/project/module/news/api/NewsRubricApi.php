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
use umicms\api\BaseHierarchicCollectionApi;
use umicms\exception\NonexistentEntityException;
use umicms\project\module\news\object\NewsRubric;

/**
 * API для работы с новостными рубриками
 */
class NewsRubricApi extends BaseHierarchicCollectionApi
{
    /**
     * {@inheritdoc}
     */
    public $collectionName = 'newsRubric';

    /**
     * Возвращает новостую рубрику по ее GUID
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить рубрику
     * @return NewsRubric
     */
    public function get($guid) {

        try {
            return $this->getCollection()->get($guid);
        } catch(IException $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find news rubric by guid "{guid}".',
                    ['guid' => $guid]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает рубрику по ее id.
     * @param int $id
     * @throws NonexistentEntityException если не удалось получить рубрику
     * @return NewsRubric
     */
    public function getById($id) {

        try {
            return $this->getCollection()->getById($id);
        } catch(IException $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find news rubric by id "{id}".',
                    ['id' => $id]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает новостую рубрику по ее URL
     * @param string $url
     * @throws NonexistentEntityException если не удалось получить рубрику
     * @return NewsRubric
     */
    public function getByUrl($url)
    {
        try {
            return $this->getElementByUrl($url);
        } catch(IException $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find news rubric by url "{url}".',
                    ['url' => $url]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Помечает рубрику на удаление.
     * @param NewsRubric $rubric
     */
    public function delete(NewsRubric $rubric) {

        $this->getCollection()->delete($rubric);
    }



}
 