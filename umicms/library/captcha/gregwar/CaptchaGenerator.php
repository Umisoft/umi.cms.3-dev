<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\captcha\gregwar;

use Gregwar\Captcha\CaptchaBuilder;
use umi\spl\config\TConfigSupport;
use umicms\captcha\exception\UnexpectedValueException;
use umicms\captcha\ICaptchaGenerator;

/**
 * Генератор для Gregwar/Captcha
 */
class CaptchaGenerator implements ICaptchaGenerator
{
    use TConfigSupport;

    /**
     * @var array $defaultOptions
     */
    protected $defaultOptions;

    /**
     * Конструктор
     * @param array $defaultOptions опции для генерации по умолчанию
     */
    public function __construct(array $defaultOptions = [])
    {
        $this->defaultOptions = $defaultOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($phrase, array $options = [])
    {
        $options = $this->mergeConfigOptions($options, $this->defaultOptions);

        $builder = $this->createCaptchaBuilder($phrase);

        if (isset($options['textColor']) && is_array($options['textColor'])) {
            if (!isset($options['textColor']['R'], $options['textColor']['G'], $options['textColor']['B'])) {
                throw new UnexpectedValueException('Cannot configure captcha. Option "textColor should be an array with keys R, G and B.');
            }
            $builder->setTextColor($options['textColor']['R'], $options['textColor']['G'], $options['textColor']['B']);
        }
        if (isset($options['backgroundColor']) && is_array($options['backgroundColor'])) {
            if (!isset($options['backgroundColor']['R'], $options['backgroundColor']['G'], $options['backgroundColor']['B'])) {
                throw new UnexpectedValueException('Cannot configure captcha. Option "backgroundColor should be an array with keys R, G and B.');
            }
            $builder->setBackgroundColor($options['backgroundColor']['R'], $options['backgroundColor']['G'], $options['backgroundColor']['B']);
        }
        if (isset($options['interpolation'])) {
            $builder->setInterpolation((bool) $options['interpolation']);
        }
        if (isset($options['distortion'])) {
            $builder->setDistortion((bool) $options['distortion']);
        }
        if (isset($options['maxFrontLines'])) {
            $builder->setMaxFrontLines($options['maxFrontLines']);
        }
        if (isset($options['maxBehindLines'])) {
            $builder->setMaxFrontLines($options['maxBehindLines']);
        }


        $width = isset($options['width']) ? (int) $options['width'] : 150;
        $height = isset($options['height']) ? (int) $options['height'] : 40;
        $font = isset($options['font']) ? $options['font'] : null;

        $builder->build($width, $height, $font);

        ob_start();

        $quality = isset($options['quality']) ? (int) $options['quality'] : 90;
        $builder->output($quality);

        return ob_get_clean();
    }


    /**
     * {@inheritdoc}
     */
    public function generatePhrase()
    {
        return $this->createCaptchaBuilder()->getPhrase();
    }

    /**
     * {@inheritdoc}
     */
    public function test($originalPhrase, $testPhrase)
    {
        return $this->createCaptchaBuilder($originalPhrase)
            ->testPhrase($testPhrase);
    }

    /**
     * Создает билдер каптчи.
     * @param string|null $phrase фраза для каптчи
     * @return CaptchaBuilder
     */
    protected function createCaptchaBuilder($phrase = null)
    {
        return new CaptchaBuilder($phrase);
    }

}
 