<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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
    public $defaultOptions = [];


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
            ->createSingleInstance([$this->defaultOptions]);
    }

}
