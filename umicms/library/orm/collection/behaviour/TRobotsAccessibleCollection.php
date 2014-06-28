<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\collection\behaviour;

use umi\orm\collection\ICollectionManager;
use umicms\exception\NotAllowedOperationException;
use umicms\orm\object\behaviour\IRobotsAccessibleObject;
use umicms\project\module\service\model\collection\RobotsCollection;
use umicms\project\module\service\model\object\Robots;

/**
 * Трейт для коллекций, поддерживающих запрещение индексирования страниц.
 */
trait TRobotsAccessibleCollection
{
    /**
     * @see ICmsCollection::getName()
     */
    abstract public function getName();
    /**
     * @see TCollectionManagerAware::getCollectionManager()
     * @return ICollectionManager
     */
    abstract protected function getCollectionManager();
    /**
     * @see ILocalizable::translate()
     */
    abstract protected function translate($message, array $placeholders = [], $localeId = null);

    /**
     * Запрещает индексацию страницы.
     * @param IRobotsAccessibleObject $page страница, добавляемая в robots.txt
     * @throws NotAllowedOperationException в случае, если операция запрещена
     * @return Robots
     */
    public function disallow(IRobotsAccessibleObject $page)
    {
        if ($page->getCollection() !== $this) {
            throw new NotAllowedOperationException($this->translate(
                'Cannot add object into robots.txt. Object from another collection "{collection}" given.',
                [
                    'collection' => $page->getCollectionName()
                ]
            ));
        }

        return $this->getRobotsCollection()->disallow($page);
    }

    /**
     * Разрешает индексацию объекта.
     * @param IRobotsAccessibleObject $page страницу, удаляемая из robots.txt
     * @throws NotAllowedOperationException в случае, если операция запрещена
     * @return mixed
     */
    public function allow(IRobotsAccessibleObject $page)
    {
        if ($page->getCollection() !== $this) {
            throw new NotAllowedOperationException($this->translate(
                'Cannot add object into robots.txt. Object from another collection "{collection}" given.',
                [
                    'collection' => $page->getCollectionName()
                ]
            ));
        }

        return $this->getRobotsCollection()->allow($page);
    }

    /**
     * Возвращает коллекцию резервных копий.
     * @return RobotsCollection
     */
    private function getRobotsCollection()
    {
        return $this->getCollectionManager()->getCollection('serviceRobots');
    }
}
