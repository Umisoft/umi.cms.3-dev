<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\slugGenerator\transliteration;

use GuzzleHttp\Client;
use umi\spl\config\TConfigSupport;
use umicms\slugGenerator\ISlugGenerator;
use URLify;

/**
 * Генерация slug путём перевода.
 */
class TranslationGenerator implements ISlugGenerator
{
    use TConfigSupport;

    /**
     * @var array $defaultOptions
     */
    protected $defaultOptions;

    /**
     * Конструктор.
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

        $client = new Client();
        $translate = $client->get($options['url'], [
                'query' => [
                    'key' => $options['key'],
                    'text' => $text,
                    'lang' => $options['lang']
                ]
            ]);

        $result = $translate->json();

        $translatedText = reset($result['text']);

        if ($translatedText) {
            $translatedText = URLify::filter($translatedText);
        } else {
            $translatedText = URLify::filter($text);
        }

        return $translatedText;
    }
}
 