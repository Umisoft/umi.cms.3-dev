<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api\controller;

use umi\form\IForm;
use umicms\orm\collection\ICmsCollection;

/**
 * Трейт для поддержки вывода форм
 * @mixin BaseRestActionController
 */
trait TCollectionFormAction
{
    /**
     * Возвращает коллекцию.
     * @param string $collectionName имя коллекции
     * @return ICmsCollection
     */
    abstract protected function getCollection($collectionName);

    /**
     * @see BaseRestActionController::getRouteVar()
     */
    abstract protected function getRouteVar($name, $default = null);

    /**
     * Возвращает форму для объектного типа коллекции.
     * @return IForm
     */
    protected function actionForm()
    {
        $collectionName = $this->getRouteVar('collection');
        $typeName = $this->getRouteVar('type');
        $formName = $this->getRouteVar('form');

        return $this->getCollection($collectionName)->getForm($typeName, $formName);
    }
}
 