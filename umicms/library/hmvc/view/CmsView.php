<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\view;

use umi\hmvc\view\View;

/**
 * Содержимое результата работы виджета или контроллера, требующее шаблонизации.
 */
class CmsView extends View
{
    /**
     * @var array $xmlAttributes имена переменных, которые будут преобразованы в атрибуты в XML.
     */
    protected $xmlAttributes = [];
    /**
     * @var array $xmlExcludes имена переменных, которые будут проигнорированы в XML.
     */
    protected $xmlExcludes = [];
    /**
     * @var array $jsonExcludes имена переменных, которые будут проигнорированы в JSON.
     */
    protected $jsonExcludes = [];

    /**
     * Устанавливает имена переменных, которые будут преобразованы в атрибуты в XML.
     * @param array $variableNames
     * @return $this
     */
    public function setXmlAttributes(array $variableNames)
    {
        $this->xmlAttributes = array_merge($this->xmlAttributes, $variableNames);
    }

    /**
     * Устанавливает имена переменных, которые будут проигнорированы в XML.
     * @param array $variableNames
     * @return $this
     */
    public function setXmlExcludes(array $variableNames)
    {
        $this->xmlExcludes = array_merge($this->xmlExcludes, $variableNames);
    }

    /**
     * Устанавливает имена переменных, которые будут проигнорированы в JSON.
     * @param array $variableNames
     * @return $this
     */
    public function setJsonExcludes(array $variableNames)
    {
        $this->jsonExcludes = array_merge($this->jsonExcludes, $variableNames);
    }

    /**
     * Возвращает имена переменных, которые будут преобразованы в атрибуты в XML.
     * @return array
     */
    public function getXmlAttributes()
    {
        return $this->xmlAttributes;
    }

    /**
     * Возвращает имена переменных, которые будут проигнорированы в XML.
     * @return array
     */
    public function getXmlExcludes()
    {
        return $this->xmlExcludes;
    }

    /**
     * Возвращает имена переменных, которые будут проигнорированы в JSON.
     * @return array
     */
    public function getJsonExcludes()
    {
        return $this->jsonExcludes;
    }

}
 