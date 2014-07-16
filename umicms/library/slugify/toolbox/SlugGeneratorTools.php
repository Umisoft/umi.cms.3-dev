<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\slugify\toolbox;

use umi\toolkit\toolbox\IToolbox;
use umi\toolkit\toolbox\TToolbox;
use umicms\exception\InvalidArgumentException;
use umicms\slugify\ISlugGenerator;
use umicms\slugify\ISlugGeneratorAware;

/**
 * Инструмент для генерации slug'ов.
 */
class SlugGeneratorTools implements IToolbox
{
    use TToolbox;

    /**
     * Имя набора инструментов
     */
    const NAME = 'slugGenerator';

    /**
     * @var array $options опции для генерации slug'ов по умолчанию
     */
    public $options = [];

    /**
     * @var string $generatorClassName имя класса стратегии генератора slug'ов
     */
    public $generatorClassName = 'umicms\slugify\filtration\FiltrationGenerator';

    /**
     * {@inheritdoc}
     */
    public function injectDependencies($object)
    {
        if ($object instanceof ISlugGeneratorAware) {
            $object->setSlugGenerator($this->getSlugGenerator());
        }
    }

    /**
     * Возвращает генератор slug'ов.
     * @throws InvalidArgumentException в случае, если неудалось создать генератор slug'ов
     * @return ISlugGenerator
     */
    public function getSlugGenerator()
    {
        return $this->getPrototype(
            $this->generatorClassName,
            ['umicms\slugify\ISlugGenerator']
        )
            ->createSingleInstance([$this->options]);
    }
}
 