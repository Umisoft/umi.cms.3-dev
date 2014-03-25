<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\api;

use umicms\api\repository\BaseObjectRepository;
use umicms\exception\NonexistentEntityException;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\structure\api\object\Layout;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * Репозиторий для работы с шаблонами.
 */
class LayoutRepository extends BaseObjectRepository implements ISiteSettingsAware
{
    use TSiteSettingsAware;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'layout';

    /**
     * Возвращает селектор для выбора шаблонов.
     * @return CmsSelector|Layout[]
     */
    public function select() {
        return $this->getCollection()->select();
    }

    /**
     * Возвращает шаблон сайта по умолчанию
     * @return Layout
     */
    public function getDefaultLayout()
    {
        return $this->get($this->getSiteDefaultLayoutGuid());
    }

    /**
     * Возвращает шаблон по GUID.
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить шаблон
     * @return Layout
     */
    public function get($guid)
    {
        try {
            return $this->getCollection()->get($guid);
        } catch(\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find layout by guid "{guid}".',
                    ['guid' => $guid]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает шаблон по id.
     * @param int $id
     * @throws NonexistentEntityException если не удалось получить шаблон
     * @return Layout
     */
    public function getById($id) {

        try {
            return $this->getCollection()->getById($id);
        } catch(\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find layout by id "{id}".',
                    ['id' => $id]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Помечает шаблон на удаление.
     * @param Layout $layout
     * @return $this
     */
    public function delete(Layout $layout)
    {
        $this->getCollection()->delete($layout);

        return $this;
    }

}
