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

use umicms\exception\NotAllowedOperationException;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\behaviour\IRobotsAccessibleObject;
use umicms\project\module\service\model\object\Robots;

/**
 * Интерфейс коллекций, поддерживающих управления доступом на индексацию поисковыми системами.
 */
interface IRobotsAccessibleCollection extends ICmsCollection
{
    /**
     * Запрещает индексацию страницы.
     * @param IRobotsAccessibleObject $page страница, добавляемая в robots.txt
     * @throws NotAllowedOperationException в случае, если операция запрещена
     * @return Robots
     */
    public function disallow(IRobotsAccessibleObject $page);

    /**
     * Разрешает индексацию страницы.
     * @param IRobotsAccessibleObject $page страницу, удаляемая из robots.txt
     * @throws NotAllowedOperationException в случае, если операция запрещена
     * @return Robots
     */
    public function allow(IRobotsAccessibleObject $page);

    /**
     * Проверяет наличие страницы в robots.txt
     * @param IRobotsAccessibleObject $page проверяемая страница
     * @throws NotAllowedOperationException в случае, если операция запрещена
     * @return bool
     */
    public function isAllowedRobots(IRobotsAccessibleObject $page);
}
