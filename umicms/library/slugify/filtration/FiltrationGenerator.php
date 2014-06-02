<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\slugify\filtration;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\spl\config\TConfigSupport;
use umicms\slugify\ISlugGenerator;
use URLify;

/**
 * Генератор slug методом фильтрации.
 */
class FiltrationGenerator implements ISlugGenerator, ILocalizable
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

        $text = preg_replace('/\b(' . join ('|', URLify::$remove_list) . ')\b/i', '', $text);

        $remove_pattern = '/[^\s_\-a-zA-Zа-яА-Я0-9]/u';
        $text = preg_replace($remove_pattern, '', $text);
        $text = str_replace('_', ' ', $text);
        $text = preg_replace('/^\s+|\s+$/', '', $text);
        $text = preg_replace('/[-\s]+/', '-', $text);
        $text = mb_strtolower($text);

        return trim(mb_substr($text, 0, $slugLength), '-');
    }
}
 