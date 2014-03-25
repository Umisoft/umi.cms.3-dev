<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\statistics\site\widget;

use umicms\hmvc\widget\BaseWidget;
use umicms\project\module\statistics\admin\metrika\model\MetrikaApi;

/**
 * Получает от Яндекс.Метрики и выводит HTML-код счетчика,
 * в точном соответствии с его настройками на сайте Метрики.
 */
class CounterWidget extends BaseWidget
{
    /**
     * @var int $counterId идентификатор счетчика Метрики
     */
    public $counterId;
    /**
     * @var MetrikaApi $api API Яндекс.Метрики
     */
    private $api;

    /**
     * Конструктор.
     * @param MetrikaApi $api API Яндекс.Метрики
     */
    public function __construct(MetrikaApi $api)
    {
        $this->api = $api;
    }

    /**
     * Выводит код счетчика.
     * @return string
     */
    public function __invoke()
    {
        if (!is_int($this->counterId)) {
            if (is_int($this->api->defaultCounterId)) {
                $this->counterId = $this->api->defaultCounterId;
            } else {
                return '';
            }
        }

        return $this->api->counterData($this->counterId)['code'];
    }
}
