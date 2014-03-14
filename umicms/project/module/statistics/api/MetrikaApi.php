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
use umicms\exception\InvalidArgumentException;

/**
 * Class MetrikaApi
 */
class MetrikaApi implements IConfigIOAware
{
    use TConfigIOAware;
    use TConfigSupport;

    /**
     * @var string $oauthToken
     */
    public $oauthToken;

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
        return [
            [
                'title' => 'Трафик',
                'methods' => [
                    [
                        'title' => 'Посещаемость',
                        'name' => 'stat/traffic/summary',
                        'fields' => [
                            'visits',
                            'page_views',
                            'visitors',
                            'new_visitors',
                            'new_visitors_perc',
                            'denial',
                            'depth',
                            'visit_time',
                        ],
                        'reports' => [
                            'name' => 'data',
                            'title' => 'Посещаемость',
                            'fields' => [
                                ['name' => 'wday', 'title' => 'День недели', 'type' => 'int'], // 0-6
                            ]
                        ]
                    ],
                    [
                        'title' => 'Вовлечение',
                        'name' => 'stat/traffic/deepness',
                        'fields' => [
                            'denial',
                            'visits',
                            'visits_percent',
                            'depth',
                            //'visit_time',
                        ],
                        'reports' => [
                            [
                                'name' => 'data_depth',
                                'title' => 'Глубина просмотра',
                                'fields' => []
                            ],
                            [
                                'name' => 'data_time',
                                'title' => 'Время, проведенное на сайте',
                                'fields' => [
                                    ['name' => 'name', 'title' => 'Длительность', 'type' => 'string'],
                                ]
                            ],
                        ],
                    ],
                    [
                        'title' => 'По времени суток',
                        'name' => 'stat/traffic/hourly',
                        'fields' => [
                            ['denial', 'avg_visits', 'depth', 'visit_time'],
                        ],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'По времени суток',
                                'fields' => [
                                    ['name' => 'hours', 'title' => 'Время', 'type' => 'string'],
                                ]
                            ],
                        ],
                    ],
                    [
                        'title' => 'Нагрузка на сайт',
                        'name' => 'stat/traffic/load',
                        'fields' => [
                            ['max_rps', 'max_users'],
                        ],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'Нагрузка на сайт',
                                'fields' => [
                                    [
                                        'name' => 'max_rps_time',
                                        'title' => 'Время наибольшей нагрузки',
                                        'type' => 'string'
                                    ],
                                    [
                                        'name' => 'max_rps_date',
                                        'title' => 'Дата наибольшей нагрузки',
                                        'type' => 'string'
                                    ],
                                    [
                                        'name' => 'max_users_date',
                                        'title' => 'Дата наибольшей посещаемости',
                                        'type' => 'string'
                                    ],
                                    [
                                        'name' => 'max_users_time',
                                        'title' => 'Время наибольшей посещаемости',
                                        'type' => 'string'
                                    ],
                                    ['name' => 'date', 'title' => 'Дата', 'type' => 'string'],
                                ]
                            ],
                        ],
                    ],
                ]
            ],
            [
                'title' => 'Источники',
                'methods' => [
                    [
                        'title' => 'Сводка',
                        'name' => 'stat/sources/summary',
                        'fields' => ['denial', 'visits', 'page_views', 'visits_delayed', 'visit_time', 'depth'],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'Нагрузка на сайт',
                                'fields' => []
                            ],
                        ],
                    ],
                    [
                        'title' => 'Сайты',
                        'name' => 'stat/sources/sites',
                        'fields' => ['denial', 'visits', 'page_views', 'visit_time', 'depth'],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'Сайты',
                                'fields' => [
                                    [
                                        'name' => 'url',
                                        'title' => 'URL сайта-источника',
                                        'type' => 'string'
                                    ],
                                ]
                            ],
                        ],
                    ],
                    [
                        'title' => 'Поисковые системы',
                        'name' => 'stat/sources/search_engines',
                        'fields' => ['denial', 'visits', 'page_views', 'visit_time', 'depth'],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'Поисковые системы',
                                'fields' => [
                                    [
                                        'name' => 'name',
                                        'title' => 'Название системы',
                                        'type' => 'string'
                                    ]
                                ],
                            ]
                        ],
                    ],
                    [
                        'title' => 'Поисковые фразы',
                        'name' => 'stat/sources/phrases',
                        'fields' => ['denial', 'visits', 'page_views', 'visit_time', 'depth'],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'Поисковые системы',
                                'fields' => [
                                    [
                                        'name' => 'phrase',
                                        'title' => 'Фраза',
                                        'type' => 'string'
                                    ],
                                    [
                                        'name' => 'search_engines',
                                        'title' => 'Фраза',
                                        'type' => 'object',
                                        'fields' => [
                                            [
                                                'name' => 'se_name',
                                                'title' => 'Имя поисковой системы',
                                                'type' => 'string'
                                            ],
                                            [
                                                'name' => 'se_page',
                                                'title' => 'Номер страницы результатов поиска',
                                                'type' => 'int'
                                            ],
                                            [
                                                'name' => 'se_url',
                                                'title' => 'Ссылка на поисковую систему',
                                                'type' => 'string'
                                            ],
                                        ]
                                    ],

                                ],
                            ]
                        ],
                    ],
                ]
            ],
            [
                'title' => 'Содержание',
                'methods' => [
                    [
                        'title' => 'Популярное содержание',
                        'name' => 'stat/content/popular',
                        'fields' => [
                            'page_views',
                            'exit',
                            'entrance',
                        ],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'Популярное содержание',
                                'fields' => [
                                    [
                                        'name' => 'url',
                                        'title' => 'URL страницы',
                                        'type' => 'string'
                                    ],
                                ],
                            ]
                        ],
                    ],
                    [
                        'title' => 'Страницы входа',
                        'name' => 'stat/content/entrance',
                        'fields' => [
                            'denial',
                            'visits',
                            'page_views',
                            'visit_time',
                            'depth',
                        ],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'Страницы входа',
                                'fields' => [
                                    [
                                        'name' => 'url',
                                        'title' => 'URL страницы сайта',
                                        'type' => 'string'
                                    ],
                                ],
                            ]
                        ],
                    ],
                    [
                        'title' => 'Страницы выхода',
                        'name' => 'stat/content/exit',
                        'fields' => [
                            'denial',
                            'visits',
                            'page_views',
                            'visit_time',
                            'depth',
                        ],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'Страницы выхода',
                                'fields' => [
                                    [
                                        'name' => 'url',
                                        'title' => 'URL страницы сайта',
                                        'type' => 'string'
                                    ],
                                ],
                            ]
                        ],
                    ],
                    [
                        'title' => 'Заголовки страниц',
                        'name' => 'stat/content/titles',
                        'fields' => ['page_views'],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'Заголовки страниц',
                                'fields' => [
                                    [
                                        'name' => 'name',
                                        'title' => 'Заголовок',
                                        'type' => 'string'
                                    ],
                                ],
                            ]
                        ],
                    ],
                ]
            ],
            [
                'title' => 'Компьютеры',
                'methods' => [
                    [
                        'title' => 'Браузеры',
                        'name' => 'stat/tech/browsers',
                        'fields' => [
                            'denial',
                            'visits',
                            'page_views',
                            'visit_time',
                            'depth',
                        ],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'Браузеры',
                                'fields' => [
                                    [
                                        'name' => 'version',
                                        'title' => 'Версия',
                                        'type' => 'string'
                                    ],
                                    [
                                        'name' => 'name',
                                        'title' => 'Браузер',
                                        'type' => 'string'
                                    ],
                                ],
                            ]
                        ],
                    ],
                    [
                        'title' => 'Операционные системы',
                        'name' => 'stat/tech/os',
                        'fields' => [
                            'denial',
                            'visits',
                            'page_views',
                            'visit_time',
                            'depth',
                        ],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'Операционные системы',
                                'fields' => [
                                    [
                                        'name' => 'name',
                                        'title' => 'Операционная система',
                                        'type' => 'string'
                                    ],
                                ],
                            ]
                        ],
                    ],
                    [
                        'title' => 'Мобильные устройства',
                        'name' => 'stat/tech/mobile',
                        'fields' => [
                            'denial',
                            'visits',
                            'page_views',
                            'visit_time',
                            'depth',
                        ],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'Мобильные устройства',
                                'fields' => [
                                    [
                                        'name' => 'name',
                                        'title' => 'Название',
                                        'type' => 'string'
                                    ],
                                ],
                            ]
                        ],
                    ],
                    [
                        'title' => 'Версии Flash',
                        'name' => 'stat/tech/flash',
                        'fields' => [
                            'denial',
                            'visits',
                            'page_views',
                            'visit_time',
                            'depth',
                        ],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'Версии Flash',
                                'fields' => [
                                    [
                                        'name' => 'name',
                                        'title' => 'Версия',
                                        'type' => 'string'
                                    ],
                                ],
                            ]
                        ],
                    ],
                    [
                        'title' => 'Наличие JavaScript',
                        'name' => 'stat/tech/javascript',
                        'fields' => [
                            'denial',
                            'visits',
                            'page_views',
                            'visit_time',
                            'depth',
                        ],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'Наличие JavaScript',
                                'fields' => [
                                    [
                                        'name' => 'name',
                                        'title' => 'Статус',
                                        'type' => 'string'
                                    ],
                                ],
                            ]
                        ],
                    ],
                ]
            ],
            [
                'title' => 'География',
                'methods' => [
                    [
                        'title' => 'Отчет по Странам мира',
                        'name' => 'stat/geo',
                        'fields' => [
                            'denial',
                            'visits',
                            'page_views',
                            'visit_time',
                            'depth',
                        ],
                        'reports' => [
                            [
                                'name' => 'data',
                                'title' => 'Отчет по Странам мира',
                                'fields' => [
                                    [
                                        'name' => 'region_type',
                                        'title' => 'Тип региона',
                                        'type' => 'string'
                                    ],
                                    [
                                        'name' => 'name',
                                        'title' => 'Регион',
                                        'type' => 'string'
                                    ],
                                ],
                            ]
                        ],
                    ],
                ]
            ],
        ];
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
    protected function findResourceConfig($resourceName)
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
            'visit_time' => ['title' => 'Время, проведенное на сайте, сек', 'type' => 'int'],
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
            $reportData[$report] = [
                'name' => $report['name'],
                'data' => $apiData[$report['name']],
                'title' => $report['title'],
            ];
        }
        return $reportData;
    }
}
