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

use Behat\Mink\Exception\ElementNotFoundException;
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
        'dock' => ['css' => 'div.umi-dock'],
        'tree' => ['css' => '.umi-tree'],
        'tableControl' => ['css' => '.umi-table-control'],
        'formControl' => ['css' => '.umi-form-control']
    ];

    /**
     * @var string $moduleName имя модуля
     */
    protected $moduleName;
    /**
     * @var string $componentName имя компонента
     */
    protected $componentName;


    /**
     * Открывает страницу компонента
     * @param array $urlParameters
     * @throws \RuntimeException если не задан модуль и компонент
     * @return $this
     *
     */
    public function open(array $urlParameters = array())
    {
        if (!$this->moduleName || !$this->componentName) {
            throw new \RuntimeException(
                'Cannot open component page. Define protected properties moduleName and componentName.'
            );
        }

        $this->waitForElements('dock');

        $this->selectDockModule($this->moduleName);

        sleep(10);

        return $this;
    }

    /**
     * Выбирает модуль в доке
     * @param string $moduleName string имя модуля
     * @throws ElementNotFoundException
     * @return $this
     */
    public function selectDockModule($moduleName)
    {
        $module = $this->getElement('dock')->find('css', 'span.' . $moduleName);

        if (!$module) {
            throw $this->elementNotFound(sprintf('Module "%s"', $moduleName));
        }

        $module->mouseOver();

        return $this;
    }

    public function selectDockComponent($componentName)
    {
        
    }


}
