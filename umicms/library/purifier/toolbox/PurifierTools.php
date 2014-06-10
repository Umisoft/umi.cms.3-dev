<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\purifier\toolbox;

use umi\toolkit\toolbox\IToolbox;
use umi\toolkit\toolbox\TToolbox;
use umicms\purifier\IPurifier;
use umicms\purifier\IPurifierAware;

/**
 * Инструмент для очистки контента.
 */
class PurifierTools implements IToolbox
{
    use TToolbox;

    /**
     * Имя набора инструментов
     */
    const NAME = 'purifier';

    /**
     * @var string $purifierClassName имя класса-очистителя контента
     */
    public $purifierClassName = 'umicms\purifier\htmlpurifier\Purifier';
    /**
     * @var array $options опции для генерации очистителя контента
     */
    public $options = [];

    /**
     * {@inheritdoc}
     */
    public function injectDependencies($object)
    {
        if ($object instanceof IPurifierAware) {
            $object->setPurifier($this->getPurifier());
        }
    }

    /**
     * Возвращает очиститель контента.
     * @return IPurifier
     */
    protected function getPurifier()
    {
        return $this->getPrototype(
            $this->purifierClassName,
            ['umicms\purifier\IPurifier']
        )
            ->createSingleInstance([$this->options]);
    }
}
 