<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\module\statistics\admin\metrika\model;

use umi\config\io\IConfigIOAware;
use umi\config\io\TConfigIOAware;
use umi\spl\config\TConfigSupport;
use umicms\api\IPublicApi;
use umicms\exception\InvalidArgumentException;

/**
 * API Яндекс.Метрики. Производит запросы к Метрике, получает статистические отчеты, информацию о счетчиках и пр.
 */
class MetrikaApi implements IConfigIOAware, IPublicApi
{
    use TConfigIOAware;
    use TConfigSupport;

    /**
     * Токен OAuth авторизации для отправки запросов к API
     * @var string $oauthToken
     */
    public $oauthToken;
    /**
     * Идентификатор счетчика, используемого по умолчанию в проекте
     * @var int $defaultCounterId
     */
    public $defaultCounterId;
    /**
     * Доступные для вызова методы статистики
     * @var array $apiResources
     */
    public $apiResources;

    /**
     * Список счетчиков статистики,
     * Возвращаемые поля описаны
     * в {@link http://api.yandex.ru/metrika/doc/ref/reference/get-counter-list.xml документации}.
     * @return array
     */
    public function listCounters()
    {
        return $this->apiRequest('counters');
    }

    /**
     * Информация о счетчике, включая код для вставки на сайт.
     * Данные описаны в {@link http://api.yandex.ru/metrika/doc/ref/reference/get-counter.xml документации}
     * @param int $counterId
     * @return array
     */
    public function counterData($counterId)
    {
        return $this->apiRequest('/counter/' . $counterId);
    }

    /**
     * Отправляет запрос статистики и возвращает ответ в формате,
     * описанном в {@link http://api.yandex.ru/metrika/doc/ref/stat/ документации Метрики}
     * @param string $resource Тип отчета, например traffic/summary
     * @param int $counterId Идентификатор счетчика Метрики
     * @param string $dateFrom Начальная дата отчета в формате YYYYMMDD
     * @param string $dateTo Конечная дата отчета в формате YYYYMMDD
     * @param string $sort Поле, по которому производится сортировка
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
     * Возвращает {@see apiResources}.
     * @return array
     */
    public function getApiResources()
    {
        return $this->apiResources;
    }

    /**
     * Посылает запрос к API Метрики.
     * @param string $resource
     * @param array $params
     * @return array
     */
    private function apiRequest($resource, array $params = [])
    {
        $query = array_merge(['oauth_token' => $this->oauthToken], $params);
        return \GuzzleHttp\get('http://api-metrika.yandex.ru/' . $resource . '.json', ['query' => $query])->json();
    }

    /**
     * Извлекает из ответа Метрики суммарные данные для вывода в график.
     * @param string $resource
     * @param array $apiData
     * @return array
     */
    public function extractChartData($resource, array $apiData)
    {
        $chartData = [];
        $dataConfig = $this->findResourceConfig($resource);
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
     * Возвращает конфигурацию ресурса статистики по названию.
     * @param string $resourceName
     * @return array
     * @throws InvalidArgumentException
     */
    private function findResourceConfig($resourceName)
    {
        $dataConfig = null;
        foreach ($this->getApiResources() as $resourceConfig) {
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
     * Возвращает свойства общих полей отчетов Метрики.
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
     * Возвращает текстовую метку поля.
     * @param $field
     * @return string
     */
    private function fieldTitle($field)
    {
        return $this->getFieldsMapping()[$field]['title'];
    }

    /**
     * Возвращает тип данных поля (int|float|percent|string).
     * @param $field
     * @return string
     */
    private function fieldType($field)
    {
        return $this->getFieldsMapping()[$field]['type'];
    }

    /**
     * Извлекает подробные данные всех предусмотренных в ресурсе отчетов.
     * @param string $resourceName
     * @param array $apiData
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
     * Возвращает свойства всех полей ресурса статистики.
     * @param string $resourceName
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
    public function normalizeDateFrom($dateString = null)
    {
        if (is_null($dateString)) {
            $dateString = 'now';
        }
        //todo system timezone
        $date = new \DateTime($dateString);
        return $date->modify('first day of this month')
            ->format('Ymd');
    }

    /**
     * Нормализует и форматирует конечную дату отчета.
     * Если дата не указана, использует текущую и проводит к концу месяца.
     * @param string|null $dateString
     * @return string
     */
    public function normalizeDateTo($dateString = null)
    {
        if (is_null($dateString)) {
            $dateString = 'now';
        }
        //todo system timezone
        $date = new \DateTime($dateString);
        return $date->modify('last day of this month')
            ->format('Ymd');
    }
}
