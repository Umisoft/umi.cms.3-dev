<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\context\admin\page;

use tests\context\BaseCmsPageObject;

/**
 * Базовый класс страницы административного компонента
 */
abstract class AdminComponentPage extends BaseCmsPageObject
{
    /**
     * @var array $elements элементы страницы
     */
    protected $elements = [
        'topBar' => ['css' => '.top-bar'],
        'dock' => ['css' => '.umi-dock'],
        'tree' => ['css' => '.umi-tree'],
        'tableControl' => ['css' => '.umi-table-control'],
        'formControl' => ['css' => '.umi-form-control']
    ];


    public function open(array $urlParameters = array())
    {
        if (!$urlParameters['module']){
            throw new \InvalidArgumentException();
        }
    }


    /**
     * Выбирает указанный административный компонент.
     * @param string $modulePath путь модуля
     * @param string $componentPath путь компонента
     */
    public function chooseModuleComponent($modulePath, $componentPath)
    {

    }

    /**
     * Выбирает указанный модуль.
     * @param string $modulePath
     */
    public function chooseModule($modulePath)
    {
        if (!$module = $this->getElement('dock')->find('css', '.dock-module')) {
            $this->elementNotFound(sprintf('Dock module %s', $modulePath));
        }
        $module->mouseOver();
        sleep(10);
    }
}
