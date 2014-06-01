<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\slugGenerator\toolbox;

use umi\toolkit\toolbox\IToolbox;
use umi\toolkit\toolbox\TToolbox;
use umicms\slugGenerator\ISlugGenerator;
use umicms\slugGenerator\ISlugGeneratorAware;

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
     * @var string $generatorClassName имя класса генерации slug'ов
     */
    public $generatorClassName = 'umicms\slugGenerator\TransliterationGenerator';
    /**
     * @var array $options опции для генерации slug'ов по умолчанию
     */
    public $options = [];

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
     * @return ISlugGenerator
     */
    public function getSlugGenerator()
    {
        return $this->getPrototype(
            $this->generatorClassName,
            ['umicms\slugGenerator\ISlugGenerator']
        )
            ->createSingleInstance([$this->options]);
    }
}
 