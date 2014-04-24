<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\callstack;

use SplStack;
use umicms\exception\RequiredDependencyException;
use umicms\exception\RuntimeException;
use umicms\orm\object\ICmsPage;

/**
 * Трейт для поддержки стека вызова страниц сайта.
 */
trait TPageCallStackAware
{
    /**
     * @var SplStack $traitPageCallStack
     */
    private $traitPageCallStack;

    public function setPageCallStack(SplStack $callStack)
    {
        $this->traitPageCallStack = $callStack;
    }

    /**
     * Добавляет текущую страницу в стек вызова.
     * @param ICmsPage $page
     * @return $this
     */
    protected function pushCurrentPage(ICmsPage $page)
    {
        $this->getPageCallStack()->push($page);
    }

    /**
     * Возвращает текущую страницу из стека.
     * @throws RuntimeException если текущая страница не определена
     * @return ICmsPage
     */
    protected function getCurrentPage()
    {
        try {
            return $this->getPageCallStack()->top();
        } catch (\RuntimeException $e) {
            throw new RuntimeException('Current page is unknown.', 0, $e);
        }
    }

    /**
     * Проверяет, определена ли текущая страница.
     * @return bool
     */
    protected function hasCurrentPage()
    {
        return !$this->getPageCallStack()->isEmpty();
    }

    /**
     * Возвращает стек вызова страниц.
     * @return SplStack|[]ICmsPage
     * @throws RequiredDependencyException
     */
    protected function getPageCallStack()
    {
        if (!$this->traitPageCallStack) {
            throw new RequiredDependencyException('Page callstack is not injected.');
        }

        return $this->traitPageCallStack;
    }

    /**
     * Проверяет наличие страницы в стеке текущих страниц.
     * @param ICmsPage $page
     * @return bool
     */
    protected function hasPage(ICmsPage $page) {
        foreach ($this->getPageCallStack() as $stackPage) {
            if ($stackPage === $page) {
                return true;
            }
        }
        return false;
    }
}
 