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
use umicms\project\module\users\object\User;

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
     * @param bool $onlyPublic выбирать только публично доступные объекты
     * @return CmsSelector|User[]
     */
    public function select($onlyPublic = true) {
        return $this->selectAll($onlyPublic);
    }

    /**
     * Возвращает пользователя по его GUID
     * @param string $guid
     * @param bool $onlyPublic выбирать только публично доступные объекты
     * @throws NonexistentEntityException если не удалось получить пользователя
     * @return User
     */
    public function get($guid, $onlyPublic = true) {
        try {
            return $this->selectByGuid($guid, $onlyPublic);
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
     * @param bool $onlyPublic выбирать только публично доступные объекты
     * @throws NonexistentEntityException если не удалось получить пользователя
     * @return User
     */
    public function getById($id, $onlyPublic = true) {

        try {
            return $this->selectById($id, $onlyPublic);
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
     * @param User $user
     * @return $this
     */
    public function delete(User $user) {

        $this->getCollection()->delete($user);

        return $this;
    }
}
 