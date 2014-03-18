<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\toolbox;

use umi\hmvc\toolbox\HmvcTools as FrameworkHmvcTools;
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
    public $dispatcherClass = 'umicms\hmvc\dispatcher\Dispatcher';

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

        parent::injectDependencies($object);
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

}
 