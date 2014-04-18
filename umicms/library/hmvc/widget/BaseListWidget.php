<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\widget;

use umi\hmvc\exception\http\HttpNotFound;
use umi\pagination\IPaginationAware;
use umi\pagination\IPaginator;
use umi\pagination\TPaginationAware;
use umicms\exception\OutOfBoundsException;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\orm\selector\CmsSelector;
use umicms\templating\helper\pagination\PaginationHelper;

/**
 * Базовый класс виджета вывода списков с постраничной навигацией.
 * Применяет условия выборки для списка и формирует постраничную навигацию, если требуется.
 */
abstract class BaseListWidget extends BaseSecureWidget implements IPaginationAware, IUrlManagerAware
{
    use TPaginationAware;
    use TUrlManagerAware;

    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'list';
    /**
     * @var int $limit максимальное количество выводимых элементов.
     * Если не указано, выводятся все элементы и постраничная навигация не будет сформирована.
     */
    public $limit;
    /**
     * @var string $pageParam имя GET-параметра, из которого берется текущая страница для построения
     * постраничной навигации. Указывается при необходимости строить постраничную навигацию.
     */
    public $pageParam;
    /**
     * @var string $type тип постраничной навигации (all, sliding, jumping, elastic).
     * Указывается при необходимости строить постраничную навигацию.
     */
    public $type;
    /**
     * @var int $pagesCount количество страниц отображаемых в ряду.
     * Указывается при необходимости строить постраничную навигацию с типом sliding, jumping, elastic.
     */
    public $pagesCount;

    /**
     * Возвращает выборку для постраничной навигации.
     * @return CmsSelector
     */
    abstract protected function getSelector();

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $selector = $this->getSelector();

        return $this->createResult(
            $this->template,
            [
                'list' => $this->applySelectorConditions($selector),
                'pagination' => $this->getPagination($selector)
            ]
        );
    }

    /**
     * Пременяет условия выборки.
     * @param CmsSelector $selector
     * @return CmsSelector
     */
    protected function applySelectorConditions(CmsSelector $selector)
    {
        if ($this->limit) {
            $selector->limit($this->limit);
        }

        return $selector;
    }

    /**
     * Возвращает контекст постраничной навигации.
     * @param CmsSelector $selector выборка объектов
     * @throws OutOfBoundsException если задан неверный тип
     * @return array
     */
    protected function getPagination(CmsSelector $selector)
    {
        static $helper;

        if (!$this->type || !$this->pageParam || !$this->limit) {
            return [];
        }

        $paginator = $this->getPaginator($selector);

        if (!$helper) {
            $helper = new PaginationHelper();
            $helper->setUrlManager($this->getUrlManager());
        }

        if (!method_exists($helper, $this->type)) {
            throw new OutOfBoundsException(
                $this->translate(
                    'Cannot create pagination. Pagination "{type}" is unknown.',
                    ['type' => $this->type]
                )
            );
        }

        $pagination = call_user_func([$helper, $this->type], $paginator, $this->pagesCount);

        return $pagination + $helper->buildLinksContext($pagination, $this->pageParam);
    }

    /**
     * Возвращает постраничную навигацию.
     * @param CmsSelector $selector выборка объектов
     * @throws HttpNotFound если текущая страница не существует
     * @return IPaginator
     */
    private function getPaginator(CmsSelector $selector)
    {
        $paginator = $this->createObjectPaginator($selector, $this->limit);

        if ($paginator->getItemsCount() > 0) {
            try {
                $paginator->setCurrentPage($this->getCurrentPage());
            } catch (\umi\pagination\exception\OutOfBoundsException $e) {
                throw new HttpNotFound(
                    $this->translate('Page not found.')
                );
            }
        }

        return $paginator;
    }

    /**
     * Возвращает текущую страницу в постраничной навигации.
     * @return int
     */
    private function getCurrentPage()
    {
        return $this->getContext()
            ->getDispatcher()
            ->getCurrentRequest()
            ->query->get($this->pageParam, 1);
    }

}
 