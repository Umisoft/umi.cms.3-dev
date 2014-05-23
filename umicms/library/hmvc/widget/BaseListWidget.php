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
use umicms\exception\InvalidArgumentException;
use umicms\exception\OutOfBoundsException;
use umicms\orm\selector\CmsSelector;
use umicms\templating\helper\PaginationHelper;

/**
 * Базовый класс виджета вывода списков с постраничной навигацией.
 * Применяет условия выборки для списка и формирует постраничную навигацию, если требуется.
 */
abstract class BaseListWidget extends BaseSecureWidget implements IPaginationAware
{
    use TPaginationAware;

    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'list';
    /**
     * @var int $limit максимальное количество выводимых элементов.
     * Если не указано, выводятся все элементы.
     */
    public $limit;
    /**
     * @var array $pagination настройки вывода постраничной навигации в формате
     * [
     *      'pageParam' => $pageParam,
     *      'type' => $type,
     *      'pagesCount' => $pagesCount
     * ], где
     *  $pageParam имя GET-параметра, из которого берется текущая страница навигации,
     *  $type тип постраничной навигации (all, sliding, jumping, elastic),
     *  $pagesCount количество страниц отображаемых в ряду
     * Если не указано, постраничная навигация не будет сформирована.
     *
     */
    public $pagination = [];

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
        $selector = $this->applySelectorConditions($this->getSelector());

        if ($this->pagination) {
            /**
             * @var IPaginator $paginator
             */
            list ($paginator, $pagination) = $this->getPagination($selector);
            $result = [
                'list' => $paginator->getPageItems(),
                'pagination' => $pagination
            ];
        } else {
            if ($this->limit) {
                $selector->limit($this->limit);
            }
            $result = ['list' => $selector];
        }

        return $this->createResult($this->template, $result);
    }

    /**
     * Пременяет условия выборки.
     * @param CmsSelector $selector
     * @return CmsSelector
     */
    protected function applySelectorConditions(CmsSelector $selector)
    {
        //TODO применение фильтров
        return $selector;
    }

    /**
     * Возвращает контекст постраничной навигации.
     * @param CmsSelector $selector выборка объектов
     * @throws InvalidArgumentException если заданы не все настройки
     * @throws OutOfBoundsException если задан неверный тип
     * @return array
     */
    protected function getPagination(CmsSelector $selector)
    {
        static $helper;

        if (!isset($this->limit)) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Cannot create pagination. Parameter "{param}" is required.',
                    ['param' => 'limit']
                )
            );
        }

        if (!isset($this->pagination['type'])) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Cannot create pagination. Option "{param}" is required for pagination.',
                    ['param' => 'type']
                )
            );
        }

        if (!isset($this->pagination['pageParam'])) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Cannot create pagination. Option "{param}" is required for pagination.',
                    ['param' => 'pageParam']
                )
            );
        }

        if ($this->pagination['type'] != 'all' && !isset($this->pagination['pagesCount'])) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Cannot create pagination. Option "{param}" is required for pagination.',
                    ['param' => 'pagesCount']
                )
            );
        }

        $paginator = $this->getPaginator($selector);

        if (!$helper) {
            $helper = new PaginationHelper();
            $helper->setUrlManager($this->getUrlManager());
        }

        if (!isset($this->pagination['type'])) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Cannot create pagination. Parameter "{param}" is required.',
                    ['param' => 'type']
                )
            );
        }

        if (!method_exists($helper, $this->pagination['type'])) {
            throw new OutOfBoundsException(
                $this->translate(
                    'Cannot create pagination. Pagination "{type}" is unknown.',
                    ['type' => $this->pagination['type']]
                )
            );
        }

        $pagesCount = isset($this->pagination['pagesCount']) ? (int) $this->pagination['pagesCount'] : null;
        $pagination = call_user_func([$helper, $this->pagination['type']], $paginator, $pagesCount);

        $pagination = array_merge($pagination, $helper->buildLinksContext($pagination, $this->pagination['pageParam']));

        return [$paginator, $pagination];
    }

    /**
     * Возвращает постраничную навигацию.
     * @param CmsSelector $selector выборка объектов
     * @throws HttpNotFound если текущая страница не существует
     * @return IPaginator
     */
    private function getPaginator(CmsSelector $selector)
    {
        $paginator = $this->createObjectPaginator($selector, (int) $this->limit);

        if ($paginator->getItemsCount() > 0) {
            try {
                $paginator->setCurrentPage($this->getCurrentPageNumber());
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
    private function getCurrentPageNumber()
    {
        return (int) $this->getContext()
            ->getDispatcher()
            ->getCurrentRequest()
            ->query->get($this->pagination['pageParam'], 1);
    }

}
 