<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\callstack;
use umicms\exception\RequiredDependencyException;
use umicms\orm\object\ICmsPage;

/**
 * Трейт для поддержки стека хлебных крошек
 */
trait TBreadcrumbsStackAware
{
    /**
     * @var \SplStack
     */
    private $traitBreadcrumbsStack;

    /**
     * Устанавливает стек хлебных крошек
     * @param \SplStack $breadcrumbsStack
     */
    public function setBreadcrumbsStack(\SplStack $breadcrumbsStack)
    {
        $this->traitBreadcrumbsStack = $breadcrumbsStack;
    }

    /**
     * Добавляет страницу в хлебные крошки
     * @param ICmsPage $page
     */
    public function pushPageToBreadcrumbs(ICmsPage $page)
    {
        $this->traitBreadcrumbsStack->push($page);
    }

    /**
     * Возвращает стек хлебных крошек
     * @return \SplStack|ICmsPage[]
     * @throws RequiredDependencyException
     */
    protected function getBreadcrumbsStack()
    {
        if (!$this->traitBreadcrumbsStack) {
            throw new RequiredDependencyException('Breadcrumbs stack is not injected.');
        }

        return $this->traitBreadcrumbsStack;
    }

    /**
     * Проверяет, находится ли страница в хлебных крошках
     * @param ICmsPage $page
     * @return bool
     */
    protected function isPageInBreadcrumbs(ICmsPage $page)
    {
        foreach ($this->getBreadcrumbsStack() as $stackPage) {
            if ($stackPage === $page) {
                return true;
            }
        }
        return false;
    }

} 