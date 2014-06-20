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

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\ElementNotFoundException;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

/**
 * Базовый объект страницы (PageObject Pattern).
 * Инкапсулирует детали сетки и верстки страницы, позволяя создавать чистые контексты.
 * @see https://code.google.com/p/selenium/wiki/PageObjects
 */
abstract class BaseCmsPageObject extends Page
{
    /**
     * Таймаут для ожидания загрузки страницы по умолчанию в миллисекундах
     * @var int $timeout
     */
    protected $defaultWaitTimeout = 5000;

    /**
     * Возаращает имя класса страницы.
     * @return string
     */
    public static function className() {
        return get_called_class();
    }

    /**
     * Ожидает загрузки элемента(ов) пока не истечет $timeout
     * @param string|array $elementNames имя элемента или массив имен элементов
     * @param int|null $timeout таймаут в миллисекундах, если не задан, будет взят $this->defaultWaitTimeout
     * @throws ElementNotFoundException если элементы не найдены за указанный таймаут
     * @return Element последний найденный элемент
     */
    protected function waitForElements($elementNames, $timeout = null)
    {
        $elementNames = (array) $elementNames;
        if (!$timeout) {
            $timeout = $this->defaultWaitTimeout;
        }

        $lastElement = null;

        $result = $this->waitFor($timeout, function() use ($elementNames, &$lastElement) {
             foreach ($elementNames as $name) {
                 if (!$this->hasElement($name)) {
                     return false;
                 } else {
                     $lastElement = $this->getElement($name);
                 }
             }

            return true;
        });

        if (!$result) {
            throw new ElementNotFoundException(
                sprintf('Element(s) "%s" is not present on the page', implode(', ', $elementNames))
            );
        }

        return $lastElement;
    }

    /**
     * Ожидает загрузки любого элемента пока не истечет $timeout
     * @param array $elementNames имя элемента или массив имен элементов
     * @param int|null $timeout таймаут в миллисекундах, если не задан, будет взят $this->defaultWaitTimeout
     * @throws ElementNotFoundException если элементы не найдены за указанный таймаут
     * @return Element первый найденный элемент
     */
    protected function waitForAnyElement(array $elementNames, $timeout = null)
    {
        if (!$timeout) {
            $timeout = $this->defaultWaitTimeout;
        }

        $result = $this->waitFor($timeout, function() use ($elementNames) {
            foreach ($elementNames as $name) {
                if ($this->hasElement($name)) {
                    return $this->getElement($name);
                }
            }

            return false;
        });

        if (!$result) {
            throw new ElementNotFoundException(
                sprintf('Elements "%s" is not present on the page', implode(' or ', $elementNames))
            );
        }

        return $result;
    }

    /**
     * Проверяет, является ли страница текущей в браузере
     * @param array $urlParameters параметры в url
     * @return bool
     */
    public function isCurrent(array $urlParameters = [])
    {

        $currentUrl = trim($this->getParameter('base_url'), '/') . '/' . trim($this->path, '/');
        $currentUrl = strtr($currentUrl, $urlParameters);

        return $currentUrl == trim($this->getSession()->getCurrentUrl(), '/');
    }

    /**
     * Проверяет наличие текста в указанном элементе страницы
     * @param Element $element
     * @param string $text
     * @return bool
     */
    protected function hasText(Element $element, $text)
    {
        $actual = preg_replace('/\s+/u', ' ', $element->getText());
        $regExp  = '/'.preg_quote($text, '/').'/ui';

        return (bool) preg_match($regExp, $actual);
    }

}
 