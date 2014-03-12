<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\api;

use umi\orm\exception\IException;
use umicms\api\BaseCollectionApi;
use umicms\exception\NonexistentEntityException;
use umicms\project\admin\api\TTrashAware;
use umicms\project\module\structure\object\Layout;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * API для работы с шаблонами.
 */
class LayoutApi extends BaseCollectionApi implements ISiteSettingsAware
{
    use TSiteSettingsAware;
    use \umicms\project\admin\api\TTrashAware;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'layout';

    /**
     * Возвращает шаблон сайта по умолчанию
     * @return Layout
     */
    public function getDefaultLayout()
    {
        return $this->get($this->getSiteDefaultLayoutGuid());
    }

    /**
     * Возвращает новость по ее GUID.
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить новость
     * @return Layout
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
}
