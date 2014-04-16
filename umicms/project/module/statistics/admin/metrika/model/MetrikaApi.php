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
use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\spl\config\TConfigSupport;
use umicms\exception\InvalidArgumentException;

/**
 * API Яндекс.Метрики. Производит запросы к Метрике, получает статистические отчеты, информацию о счетчиках и пр.
 */
class MetrikaApi implements IConfigIOAware, ILocalizable
{
    use TConfigIOAware;
    use TConfigSupport;
    use TLocalizable;

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
     * Префикс для локализуемых меток API метрики.
     * @var string $prefixLocalizable
     */
    private $prefixLocalizable = 'component:metrika:';

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
     * Извлекает подробные данные всех предусмотренных в ресурсе отчетов.
     * @param string $resourceName имя запрашеваемого ресурса
     * @param array $apiData ответ на запрос
     * @return array
     */
    public function extractReportData($resourceName, $apiData)
    {
        $reportData = [];
        $dataConfig = $this->findResourceConfig($resourceName);
        $reports = $dataConfig['reports'];

        foreach ($reports as $report) {
            $reportInfo = [
                'displayName' => $this->getLabel($resourceName, $report['name']),
                'data' => $apiData[$report['name']],
            ];
            if (isset($report['graph'])) {
                $reportInfo['graph'] = $report['graph'];
            }
            $reportData[] = $reportInfo;
        }
        return $reportData;
    }

    /**
     * Возвращает свойства всех полей ресурса статистики.
     * @param string $resourceName имя запрашеваемого ресурса
     * @param array $apiData ответ на запрос
     * @return array
     */
    public function extractFieldsLabel($resourceName, $apiData)
    {
        $metadata = [];

        $resourceConfig = $this->findResourceConfig($resourceName);
        $reports = $resourceConfig['reports'];

        foreach ($reports as $report) {
            $metadata = array_merge($metadata, $this->getFieldsReport($apiData[$report['name']], $resourceName));
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
        $date = new \DateTime(is_null($dateString) ? 'now' : $dateString);

        if (is_null($dateString)) {
            $date->modify('first day of this month');
        }
        //todo system timezone
        return $date->format('Ymd');
    }

    /**
     * Нормализует и форматирует конечную дату отчета.
     * Если дата не указана, использует текущую и проводит к концу месяца.
     * @param string|null $dateString
     * @return string
     */
    public function normalizeDateTo($dateString = null)
    {
        $date = new \DateTime(is_null($dateString) ? 'now' : $dateString);

        if (is_null($dateString)) {
            $date->modify('last day of this month');
        }
        //todo system timezone
        return $date->format('Ymd');
    }

    /**
     * Извлекает список полей из отчёта.
     * @param array $data ответ на запрос
     * @param string $reportName название отчёта
     * @return array
     */
    public function getFieldsReport($data, $reportName)
    {
        $fields = [];

        if (isset($data[0])) {
            foreach($data[0] as $k => $v) {
                $fields[$k] = $this->getLabel($reportName, $k);
                if (is_array($v)) {
                    $fields = array_merge($fields, $this->getFieldsReport($v, $reportName));
                }
            }
        }

        return $fields;
    }

    /**
     * Возвращает метку поля.
     * @param string $prefix префикс
     * @param string $name имя поля
     * @return string
     */
    public function getLabel($prefix, $name = null)
    {
        $prefix = str_replace('/', ':', $prefix);

        return $this->translate($this->prefixLocalizable . $prefix . (is_null($name) ? $name : ':' . $name));
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
     * Возвращает конфигурацию ресурса статистики по названию.
     * @param string $resourceName
     * @return array
     * @throws InvalidArgumentException
     */
    private function findResourceConfig($resourceName)
    {
        $dataConfig = null;
        foreach ($this->getApiResources() as $resourceConfig) {
            foreach ($resourceConfig['resources'] as $methodConfig) {
                if ($methodConfig['name'] == $resourceName) {
                    $dataConfig = $methodConfig;
                    break 2;
                }
            }
        }
        if (is_null($dataConfig)) {
            throw new InvalidArgumentException(
                $this->translate("Wrong resource query")
            );
        }
        return $dataConfig;
    }
}
