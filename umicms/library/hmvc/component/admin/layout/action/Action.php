<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\admin\layout\action;

/**
 * REST-действие
 */
class Action
{
    /**
     * Действие на модификацию данных
     */
    const TYPE_MODIFY = 'modify';
    /**
     * Действие на чтение данных
     */
    const TYPE_QUERY = 'query';

    /**
     * @var string $type тип действия
     */
    protected $type = self::TYPE_QUERY;
    /**
     * @var string $sourceUrl url ресурса
     */
    protected $sourceUrl;

    /**
     * Конструктор.
     * @param string $sourceUrl url ресурса
     * @param string $type тип действия Action::TYPE_QUERY, Action::TYPE_MODIFY
     */
    public function __construct($sourceUrl, $type = self::TYPE_QUERY)
    {
        $this->sourceUrl = $sourceUrl;
        $this->type = $type;
    }

    /**
     * Возвращает информацию о действии.
     * @return array
     */
    public function build()
    {
        return [
            'type' => $this->type,
            'source' => $this->sourceUrl
        ];
    }
}
 