<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\toolbox;

use SplDoublyLinkedList;
use SplStack;
use umi\hmvc\toolbox\HmvcTools as FrameworkHmvcTools;
use umicms\hmvc\callstack\IPageCallStackAware;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\hmvc\url\IUrlManager;
use umicms\hmvc\url\IUrlManagerAware;

/**
 * {@inheritdoc}
 */
class HmvcTools extends FrameworkHmvcTools
{

    /**
     * @var string $urlManagerClass имя класса URL-менеджера
     */
    public $urlManagerClass = 'umicms\hmvc\url\UrlManager';
    /**
     * {@inheritdoc}
     */
    public $dispatcherClass = 'umicms\hmvc\dispatcher\CmsDispatcher';

    /**
     * @var SplStack $pageCallStack стек вызова страниц
     */
    protected $pageCallStack;

    /**
     * {@inheritdoc}
     */
    public function getService($serviceInterfaceName, $concreteClassName)
    {
        if ($serviceInterfaceName == 'umicms\hmvc\url\IUrlManager') {
            return $this->getUrlManager();
        }

        return parent::getService($serviceInterfaceName, $concreteClassName);
    }

    /**
     * {@inheritdoc}
     */
    public function injectDependencies($object)
    {
        if ($object instanceof IUrlManagerAware) {
            $object->setUrlManager($this->getUrlManager());
        }

        if ($object instanceof IPageCallStackAware) {
            $object->setPageCallStack($this->getPageCallStack());
        }

        parent::injectDependencies($object);
    }

    /**
     * Возвращает диспетчер MVC-компонентов.
     * @return CmsDispatcher
     */
    protected function getDispatcher()
    {
        return $this->getPrototype(
            $this->dispatcherClass,
            ['umicms\hmvc\dispatcher\CmsDispatcher']
        )
            ->createSingleInstance();
    }

    /**
     * Создает и возвращает URL-менеджер.
     * @return IUrlManager
     */
    protected function getUrlManager()
    {
        return $this->getPrototype(
            $this->urlManagerClass,
            ['umicms\hmvc\url\IUrlManager']
        )
            ->createSingleInstance();
    }

    /**
     * Возвращает стек вызова страниц
     * @return SplStack
     */
    protected function getPageCallStack()
    {
        if (!$this->pageCallStack) {
            $this->pageCallStack = new SplStack();
            $this->pageCallStack->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO);
        }

        return $this->pageCallStack;
    }

}
 