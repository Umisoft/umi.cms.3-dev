<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\module\statistics\api;

use umi\config\io\IConfigIOAware;
use umi\config\io\TConfigIOAware;
use umi\spl\config\TConfigSupport;
use umicms\api\IPublicApi;
use umicms\exception\InvalidArgumentException;

/**
 * Class MetrikaApi
 */
class MetrikaApi implements IConfigIOAware, IPublicApi
{
    use TConfigIOAware;
    use TConfigSupport;

    /**
     * @var string $oauthToken
     */
    public $oauthToken;
    /**
     * @var array $apiResources
     */
    public $apiResources;

    /**
     * Список счетчиков статистики, каждый - массив вида
     * <code>
     * [
     *   'site' => string
     *   'code_status' => string
     *   'permission' => string
     *   'name' => null|string
     *   'id' => string
     *   'type' => string
     *   'owner_login' => string
     * ]
     * </code>
     * @return array
     */
    public function listCounters()
    {
        return $this->apiRequest('counters');
    }

    /**
     * @param $resource
     * @param $counterId
     * @param null $dateFrom
     * @param null $dateTo
     * @param null $sort
     * @return array
     */
    public function statQuery($resource, $counterId, $dateFrom = null, $dateTo = null, $sort = null)
    {
        $params = [
            'id' => (int) $counterId
        ];
        if (!is_null($dateFrom)) {
            $params['date1'] = $dateFrom;
        }
        if (!is_null($dateTo)) {
            $params['date2'] = $dateTo;
        }
        if (!is_null($sort)) {
            $params['sort'] = $sort;
        }
        return $this->apiRequest($resource, $params);
    }

    /**
     * К каким ресурсам можно производить запросы
     * @return array
     */
    public function listResources()
    {
        return $this->apiResources;
    }

    /**
     * @param $path
     * @param array $params
     * @return array
     */
    private function apiRequest($path, array $params = [])
    {
        $query = array_merge(['oauth_token' => $this->oauthToken], $params);
        $result = file_get_contents('http://api-metrika.yandex.ru/' . $path . '.json?' . http_build_query($query));
        //todo errors
        return json_decode($result, true);
    }

    /**
     * @param $resourceName
     * @param array $apiData
     * @return array
     */
    public function extractChartData($resourceName, array $apiData)
    {
        $chartData = [];
        $dataConfig = $this->findResourceConfig($resourceName);
        $fields = $dataConfig['fields'];
        foreach ($fields as $field) {
            $chartData[$field] = [
                'name' => $field,
                'title' => $this->fieldTitle($field),
                'type' => $this->fieldType($field),
                'value' => $apiData['totals'][$field]
            ];
        }
        return $chartData;
    }

    /**
     * @param $resourceName
     * @return null
     * @throws InvalidArgumentException
     */
    private function findResourceConfig($resourceName)
    {
        $dataConfig = null;
        foreach ($this->listResources() as $resourceConfig) {
            foreach ($resourceConfig['methods'] as $methodConfig) {
                if ($methodConfig['name'] == $resourceName) {
                    $dataConfig = $methodConfig;
                    break 2;
                }
            }
        }
        if (is_null($dataConfig)) {
            throw new InvalidArgumentException("Wrong resource query");
        }
        return $dataConfig;
    }

    /**
     * @return array
     */
    public function getFieldsMapping()
    {
        return [
            'visits' => ['title' => 'Визиты', 'type' => 'int'],
            'page_views' => ['title' => 'Просмотры', 'type' => 'int'],
            'visitors' => ['title' => 'Посетители', 'type' => 'int'],
            'new_visitors' => ['title' => 'Новые посетители', 'type' => 'int'],
            'new_visitors_perc' => ['title' => 'Процент новых посетителей', 'type' => 'percent'],
            'denial' => ['title' => 'Отказы', 'type' => 'percent'],
            'depth' => ['title' => 'Глубина просмотра', 'type' => 'float'],
            'visit_time' => ['title' => 'Время, проведенное на сайте, сек', 'type' => 'int'],
            'avg_visits' => ['title' => 'Среднее число визитов', 'type' => 'float'],
            'visits_delayed' => ['title' => 'visits_delayed', 'type' => 'int'],
            'entrance' => ['title' => 'Входов', 'type' => 'int'],
            'exit' => ['title' => 'Выходов', 'type' => 'int'],
        ];
    }

    /**
     * @param $field
     * @return string
     */
    private function fieldTitle($field)
    {
        return $this->getFieldsMapping()[$field]['title'];
    }

    /**
     * @param $field
     * @return string
     */
    private function fieldType($field)
    {
        return $this->getFieldsMapping()[$field]['type'];
    }

    /**
     * @param $resourceName
     * @param $apiData
     * @return array
     */
    public function extractReportData($resourceName, $apiData)
    {
        $reportData = [];
        $dataConfig = $this->findResourceConfig($resourceName);
        $reports = $dataConfig['reports'];

        foreach ($reports as $report) {
            $reportData[] = [
                'name' => $report['name'],
                'data' => $apiData[$report['name']],
                'title' => $report['title'],
            ];
        }
        return $reportData;
    }

    /**
     *
     * @param $resourceName
     * @return array
     */
    public function extractFieldsMetadata($resourceName)
    {
        $metadata = [];

        $resourceConfig = $this->findResourceConfig($resourceName);
        foreach ($resourceConfig['fields'] as $resourceField) {
            $metadata[$resourceField] = [
                'title' => $this->fieldTitle($resourceField),
                'type' => $this->fieldType($resourceField),
            ];
        }

        $reports = $resourceConfig['reports'];

        foreach ($reports as $report) {
            foreach ($report['fields'] as $reportField) {
                $metadata[$reportField['name']] = ['title' => $reportField['title'], 'type' => $reportField['type']];
            }
        }
        return $metadata;
    }

    /**
     * Нормализует и форматирует начальную дату отчета.
     * Если дата не указана, использует текущую и проводит к началу месяца.
     * @param string|null $dateString
     * @return string
     */
    public function normalizeDateFrom($dateString)
    {
        if(is_null($dateString)){
            $dateString = 'now';
        }
        //todo system timezone
        $date = new \DateTime($dateString);
        return $date->modify('first day of this month')->format('Ymd');
    }

    /**
     * Нормализует и форматирует конечную дату отчета.
     * Если дата не указана, использует текущую и проводит к концу месяца.
     * @param string|null $dateString
     * @return string
     */
    public function normalizeDateTo($dateString = null)
    {
        if(is_null($dateString)){
            $dateString = 'now';
        }
        //todo system timezone
        $date = new \DateTime($dateString);
        return $date->modify('last day of this month')->format('Ymd');
    }
}
