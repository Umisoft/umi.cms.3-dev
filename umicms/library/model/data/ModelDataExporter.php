<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\model\data;

use umicms\model\Model;

/**
 * Экспортирует данные модели.
 */
class ModelDataExporter
{
    /**
     * @var Model $model
     */
    protected $model;

    /**
     * Конструктор.
     * @param Model $model модель данных
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Возвращает дамп данных модели.
     * @param int|null $limit количество записей в дампе.
     * @param int|null $offset смещение
     * @return array
     */
    public function getDump($limit = null, $offset = null)
    {

    }
}
 