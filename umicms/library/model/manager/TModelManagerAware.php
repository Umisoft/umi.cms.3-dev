<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\model\manager;

use umicms\exception\RequiredDependencyException;

/**
 * Трейт для внедрения менеджера моделей данных.
 */
trait TModelManagerAware
{
    /**
     * @var ModelManager $traitModelManager менеджер объектов
     */
    private $traitModelManager;

    /**
     * @see IModelManagerAware::setModelManager()
     */
    public function setModelManager(ModelManager $modelManager)
    {
        $this->traitModelManager = $modelManager;
    }

    /**
     * Возвращает менеджер объектов
     * @throws RequiredDependencyException если менеджер объектов не установлен
     * @return ModelManager
     */
    protected function getModelManager()
    {
        if (!$this->traitModelManager) {
            throw new RequiredDependencyException(sprintf(
                'Model manager is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitModelManager;
    }
}
 