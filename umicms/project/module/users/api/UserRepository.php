<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\api;

use umicms\api\repository\BaseObjectRepository;
use umicms\api\repository\TRecycleAwareRepository;
use umicms\exception\NonexistentEntityException;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\users\api\object\BaseUser;

/**
 * Репозиторий для работы с пользователями.
 */
class UserRepository extends BaseObjectRepository
{
    use TRecycleAwareRepository;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'user';

    /**
     * Возвращает селектор для выбора сюжетов.
     * @return CmsSelector|BaseUser[]
     */
    public function select() {
        return $this->getCollection()->select();
    }

    /**
     * Возвращает пользователя по его GUID
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить пользователя
     * @return BaseUser
     */
    public function get($guid) {
        try {
            return $this->getCollection()->get($guid);
        } catch(\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find user by guid "{guid}".',
                    ['guid' => $guid]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает пользователя по его id.
     * @param int $id
     * @throws NonexistentEntityException если не удалось получить пользователя
     * @return BaseUser
     */
    public function getById($id) {

        try {
            return $this->getCollection()->getById($id);
        } catch(\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find user by id "{id}".',
                    ['id' => $id]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Помечает пользователя на удаление.
     * @param BaseUser $user
     * @return $this
     */
    public function delete(BaseUser $user) {

        $this->getCollection()->delete($user);

        return $this;
    }
}
 