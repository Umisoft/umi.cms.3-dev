<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\object;

use umicms\orm\object\CmsElement;
use umicms\orm\object\ICmsPage;

/**
 * Базовый элемент структуры.
 *
 * @property string $componentPath путь до компонента-обработчика
 */
abstract class StructureElement extends CmsElement implements ICmsPage
{
    /**
     *  Имя поля для хранения пути компонента-обработчика
     */
    const FIELD_COMPONENT_PATH = 'componentPath';

}
