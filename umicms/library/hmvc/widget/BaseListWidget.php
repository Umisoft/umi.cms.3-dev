<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\widget;

use umi\hmvc\exception\http\HttpNotFound;
use umi\orm\selector\ISelector;
use umi\pagination\IPaginationAware;
use umi\pagination\TPaginationAware;
use umicms\exception\InvalidArgumentException;
use umicms\exception\OutOfBoundsException;
use umicms\hmvc\view\CmsView;
use umicms\orm\object\ICmsObject;
use umicms\orm\selector\TSelectorConfigurator;
use umicms\pagination\CmsPaginator;

/**
 * Базовый класс виджета вывода списков с постраничной навигацией.
 * Применяет условия выборки для списка и формирует постраничную навигацию, если требуется.
 */
abstract class BaseListWidget extends BaseCmsWidget implements IPaginationAware
{
    use TPaginationAware;
    use TSelectorConfigurator;

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
     * @var int $offset сдвиг.
     * Игнорируется при заданных настройках вывода постраничной навигации
     */
    public $offset;
    /**
     * @var array $options настройки выборки.
     * <ul>
     * <li>fields - имена полей, указанные через запятую, которые будут загружены для объектов,</li>
     * <li>orderBy - настройки сортировки объектов в списке, заданные массивом, где ключами являются пути к полям, по которым выполняется сортировка, а значениями - направление сортировки,</li>
     * <li>filters - настройки ограничения выборки, заданные массивом, где ключами являются пути к полям, ограничивающих выборку, а значениями условия ограничения,</li>
     * <li>with - настройки загрузки значений полей свяи типа belongsTo, заданные массивом, где ключами являются пути к полям связи, а значениями - заданные через запятую имена полей связанного объекта, с которыми он будет загружен </li>
     * </ul>
     */
    public $options = [];
    /**
     * @var array $pagination настройки вывода постраничной навигации в формате
     * <br/>[<br/>
     *      'pageParam' => $pageParam,<br/>
     *      'type' => $type,<br/>
     *      'pagesCount' => $pagesCount<br/>
     * ]<br/>, где
     *  $pageParam имя GET-параметра, из которого берется текущая страница навигации,
     *  $type тип постраничной навигации (all, sliding, elastic),
     *  $pagesCount количество страниц отображаемых в ряду
     * Если не указано, постраничная навигация не будет сформирована.
     *
     */
    public $pagination = [];
    /**
     * @var bool $fullyLoad признак необходимости загружать все свойства объектов списка.
     * Список полей для загрузки, занный опциями, при значении true игнорируется.
     */
    public $fullyLoad;

    /**
     * Возвращает выборку для постраничной навигации.
     * @return ISelector
     */
    abstract protected function getSelector();


    /**
     * Формирует результат работы виджета.
     *
     * Для шаблонизации доступны следущие параметры:
     * @templateParam array|umi\orm\selector\ISelector $list список объектов
     * @templateParam umicms\pagination\CmsPaginator $paginator постраничная навигация, если была сформирована
     *
     * @return CmsView
     */
    public function __invoke()
    {
        $selector = $this->applySelectorConditions($this->getSelector());

        if ($this->pagination) {

            $paginator = $this->getPaginator($selector);
            $result = [
                'list' => $paginator->getPageItems()
            ];

            if ($paginator->getPagesCount() > 1) {
                $result['paginator'] = $paginator;
            }

        } else {
            if ($this->limit) {
                $selector->limit($this->limit, $this->offset);
            }
            $result = ['list' => $selector];
        }
        return $this->createResult($this->template, $result);
    }

    /**
     * Пременяет условия выборки.
     * @param ISelector $selector
     * @return ISelector
     */
    protected function applySelectorConditions(ISelector $selector)
    {
        if (!$this->fullyLoad) {
            $fields = ICmsObject::FIELD_DISPLAY_NAME;
            if (isset($this->options['fields'])) {
                $fields = $fields . ',' . $this->options['fields'];
            }
            $this->applySelectorSelectedFields($selector, $fields);
        }

        if (isset($this->options['with']) && is_array($this->options['with'])) {
            $this->applySelectorWith($selector, $this->options['with']);
        }

        if (isset($this->options['orderBy']) && is_array($this->options['orderBy'])) {
            $this->applySelectorOrderBy($selector, $this->options['orderBy']);
        }

        if (isset($this->options['filters']) && is_array($this->options['filters'])) {
            $this->applySelectorConditionFilters($selector, $this->options['filters']);
        }

        return $selector;
    }

    /**
     * Возвращает контекст постраничной навигации.
     * @param ISelector $selector выборка объектов
     * @throws InvalidArgumentException если заданы не все настройки
     * @throws OutOfBoundsException если задан неверный тип
     * @return CmsPaginator
     */
    protected function getPaginator(ISelector $selector)
    {
        if (!isset($this->limit)) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Cannot create pagination. Parameter "{param}" is required.',
                    ['param' => 'limit']
                )
            );
        }

        if (!isset($this->pagination['type'])) {
            $this->pagination['type'] = 'all';
        }

        if (!isset($this->pagination['pageParam'])) {
            $this->pagination['pageParam'] = 'p';
        }

        if ($this->pagination['type'] != 'all' && !isset($this->pagination['pagesCount'])) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Cannot create pagination. Option "{param}" is required for pagination.',
                    ['param' => 'pagesCount']
                )
            );
        }

        $paginator = $this->createPaginator($selector);

        $paginator->setType($this->pagination['type']);
        $paginator->setPageParam($this->pagination['pageParam']);
        if (isset($this->pagination['pagesCount'])) {
            $paginator->setPagesCount($this->pagination['pagesCount']);
        }

        return $paginator;
    }

    /**
     * Возвращает постраничную навигацию.
     * @param ISelector $selector выборка объектов
     * @throws HttpNotFound если текущая страница не существует
     * @return CmsPaginator
     */
    protected function createPaginator(ISelector $selector)
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
 