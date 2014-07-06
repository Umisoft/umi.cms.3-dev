<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\context;

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;

/**
 * Базовый Контекст для тестирования UMI.CMS
 */
abstract class BaseCmsContext extends PageObjectContext
{
    /**
     * Открывает страницу в браузере
     * @param string $className
     * @param array $urlParameters параметры внутри url
     * @return BaseCmsPageObject
     */
    public function browsePage($className, array $urlParameters = [])
    {
        return $this->getPage($className)->open($urlParameters);
    }

    /**
     * Провелить шаг.
     * @param string $message
     * @throws \Exception
     */
    public function fail($message = 'Step failed.')
    {
        throw new \Exception($message);
    }
}
 