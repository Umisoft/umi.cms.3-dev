<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\statistics\admin\metrika\controller;

use umi\http\Response;
use umi\orm\persister\TObjectPersisterAware;
use umicms\exception\InvalidArgumentException;
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\module\statistics\api\MetrikaApi;

/**
 * Контроллер операций.
 */
class ActionController extends BaseRestActionController
{
    /**
     * @var MetrikaApi $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param MetrikaApi $api
     */
    public function __construct(MetrikaApi $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    protected function getQueryActions()
    {
        return ['counter', 'viewCounter'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getModifyActions()
    {
        return [];
    }

    /**
     * Возвращает данные статистики для ресурса, собранные по указанному счетчику
     * @return Response
     * @throws InvalidArgumentException
     */
    public function actionCounter()
    {
        $counterId = $this->getRouteVar('counterId');
        $resource = $this->getQueryVar('resource');
        $dateFrom = $this->api->normalizeDateFrom($this->getQueryVar('date1'));
        $dateTo = $this->api->normalizeDateTo($this->getQueryVar('date2'));
        if (is_null($counterId)) {
            throw new InvalidArgumentException("Incorrect Metrika counter id");
        }
        if (is_null($resource)) {
            throw new InvalidArgumentException("Incorrect resource queried");
        }

        $apiData = $this->api->statQuery(
            $resource,
            $counterId,
            $dateFrom,
            $dateTo,
            $this->getQueryVar('sort', 'id')
        );

        $response = [
            'api_data' => [
                'totals' => $apiData['totals'],
                'metadata' => $this->api->extractFieldsMetadata($resource),
                'report' => $this->api->extractReportData($resource, $apiData),
                'max' => $apiData['max'],
                'min' => $apiData['min'],
            ],
        ];
        return $response;
    }

    /**
     * @return Response
     */
    public function actionCounters()
    {
        $counters = $this->api->listCounters();
        return $counters;
    }
}
