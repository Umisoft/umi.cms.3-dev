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
use umicms\project\admin\api\controller\DefaultRestActionController;
use umicms\project\module\statistics\admin\metrika\model\MetrikaApi;

/**
 * Контроллер операций компонента Метрики.
 */
class ActionController extends DefaultRestActionController
{
    /**
     * API для запросов к Яндекс.Метрике
     * @var MetrikaApi $api
     */
    protected $api;

    /**
     * Конструктор. Внедряет {@see $api API Метрики}.
     * @param MetrikaApi $api
     */
    public function __construct(MetrikaApi $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryActions()
    {
        return ['counter', 'counters', 'navigation'];
    }

    /**
     * {@inheritdoc}
     */
    public function getModifyActions()
    {
        return [];
    }

    /**
     * Возвращает данные статистики для ресурса, собранные по указанному счетчику.
     * @return Response
     * @throws InvalidArgumentException
     */
    public function actionCounter()
    {
        $counterId = $this->getRequiredQueryVar('counterId');
        $resource = $this->getRequiredQueryVar('resource');
        $dateFrom = $this->api->normalizeDateFrom($this->getQueryVar('date1'));
        $dateTo = $this->api->normalizeDateTo($this->getQueryVar('date2'));
        if (is_null($counterId)) {
            throw new InvalidArgumentException(
                $this->translate("Incorrect Metrika counter id")
            );
        }
        if (is_null($resource)) {
            throw new InvalidArgumentException(
                $this->translate("Incorrect resource queried")
            );
        }

        $apiData = $this->api->statQuery(
            $resource,
            $counterId,
            $dateFrom,
            $dateTo,
            $this->getQueryVar('sort', 'id')
        );

        $response = [
            'labels' => $this->api->extractFieldsLabel($resource, $apiData),
            'report' => $this->api->extractReportData($resource, $apiData),
            'date1' => $apiData['date1'],
            'date2' => $apiData['date2'],
            'rows' => $apiData['rows'],
            'totals' => $apiData['totals'],
            'max' => $apiData['max'],
            'min' => $apiData['min']
        ];
        if (isset($apiData['errors'])) {
            foreach($apiData['errors'] as $error) {
                $response['errors'] = [
                    'text' => $this->translate($error['code']),
                    'code' => $error['code']
                ];
            }
        }

        return $response;
    }

    /**
     * Возвращает список доступных счетчиков Метрики.
     * @return array
     */
    public function actionCounters()
    {
        $listCounters = $this->api->listCounters();

        foreach ($listCounters['counters'] as &$counter) {
            $counter['code_status'] = $this->translate($counter['code_status']);
        }

        $counters['labels'] = $this->api->getFieldsReport($listCounters['counters'], 'counters');
        $counters['counters'] = $listCounters['counters'];

        return $counters;
    }

    /**
     * Возвращает список доступных отчетов.
     * @return array
     */
    public function actionNavigation()
    {
        $counterId = $this->getRequiredQueryVar('counterId');
        $navigation = [];
        $apiResourceGroups = $this->api->getApiResources();
        foreach ($apiResourceGroups as $group => $resourceGroup) {
            $navigationGroup = [];
            $navigationGroup['displayName'] = $this->api->getLabel($group);
            $navigationGroup['children'] = [];
            foreach ($resourceGroup['resources'] as $resource) {
                $query = ['counterId'=>$counterId, 'resource'=>$resource['name']];
                $navigationGroup['children'][] = [
                    'displayName' => $this->api->getLabel($resource['name']),
                    'uri' => '/counter/?' . http_build_query($query),
                    'resource' => $resource['name'],
                ];
            }
            $navigation[] = $navigationGroup;
        }
        return $navigation;
    }
}
