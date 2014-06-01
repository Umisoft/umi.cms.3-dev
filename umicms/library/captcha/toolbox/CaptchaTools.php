<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\captcha\toolbox;

use umi\toolkit\toolbox\IToolbox;
use umi\toolkit\toolbox\TToolbox;
use umicms\captcha\ICaptchaAware;
use umicms\captcha\ICaptchaGenerator;

/**
 * Инструментарий для генерации captcha.
 */
class CaptchaTools implements IToolbox
{
    /**
     * Имя набора инструментов
     */
    const NAME = 'captcha';

    use TToolbox;

    /**
     * @var string $generatorClassName имя класса-генератора каптчи
     */
    public $generatorClassName = 'umicms\captcha\gregwar\CaptchaGenerator';
    /**
     * @var array $options опции для генерации каптчи по умолчанию
     */
    public $options = [];


    /**
     * {@inheritdoc}
     */
    public function injectDependencies($object)
    {
        if ($object instanceof ICaptchaAware) {
            $object->setCaptchaGenerator($this->getCaptchaGenerator());
        }
    }

    /**
     * Возвращает генератор captcha.
     * @return ICaptchaGenerator
     */
    protected function getCaptchaGenerator()
    {
        return $this->getPrototype(
            $this->generatorClassName,
            ['umicms\captcha\ICaptchaGenerator']
        )
            ->createSingleInstance([$this->options]);
    }

}
