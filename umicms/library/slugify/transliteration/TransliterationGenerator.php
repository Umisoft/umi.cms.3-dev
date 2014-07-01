<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\slugify\transliteration;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\spl\config\TConfigSupport;
use umicms\slugify\ISlugGenerator;
use URLify;

/**
 * Генератор slug методом транслитерации.
 */
class TransliterationGenerator implements ISlugGenerator, ILocalizable
{
    use TConfigSupport;
    use TLocalizable;

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
    public function generate($text, array $options = [])
    {
        $options = $this->mergeConfigOptions($options, $this->defaultOptions);

        $slugLength = 60;
        if (isset($options['slugLength'])) {
            $slugLength = $options['slugLength'];
        }

        return URLify::filter($text, $slugLength);
    }
}
 