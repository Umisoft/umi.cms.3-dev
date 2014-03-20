<?php
return [
    [
        'displayName' => 'component:metrika:traffic',
        'methods' => [
            [
                'displayName' => 'component:metrika:trafficSummary',
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
                    [
                        'name' => 'data',
                        'displayName' => 'component:metrika:trafficSummaryData',
                        'fields' => [
                            [
                                'name' => 'wday',
                                'displayName' => 'component:metrika:wday',
                                'type' => 'int'
                            ], // 0-6
                        ]
                    ]
                ]
            ],
            [
                'displayName' => 'component:metrika:trafficDeepness',
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
                        'displayName' => 'Глубина просмотра',
                        'fields' => []
                    ],
                    [
                        'name' => 'data_time',
                        'displayName' => 'Время, проведенное на сайте',
                        'fields' => [
                            ['name' => 'name', 'displayName' => 'Длительность', 'type' => 'string'],
                        ]
                    ],
                ],
            ],
            [
                'displayName' => 'По времени суток',
                'name' => 'stat/traffic/hourly',
                'fields' => [
                    ['denial', 'avg_visits', 'depth', 'visit_time'],
                ],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'По времени суток',
                        'fields' => [
                            ['name' => 'hours', 'displayName' => 'Время', 'type' => 'string'],
                        ]
                    ],
                ],
            ],
            [
                'displayName' => 'Нагрузка на сайт',
                'name' => 'stat/traffic/load',
                'fields' => [
                    ['max_rps', 'max_users'],
                ],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'Нагрузка на сайт',
                        'fields' => [
                            [
                                'name' => 'max_rps_time',
                                'displayName' => 'Время наибольшей нагрузки',
                                'type' => 'string'
                            ],
                            [
                                'name' => 'max_rps_date',
                                'displayName' => 'Дата наибольшей нагрузки',
                                'type' => 'string'
                            ],
                            [
                                'name' => 'max_users_date',
                                'displayName' => 'Дата наибольшей посещаемости',
                                'type' => 'string'
                            ],
                            [
                                'name' => 'max_users_time',
                                'displayName' => 'Время наибольшей посещаемости',
                                'type' => 'string'
                            ],
                            ['name' => 'date', 'displayName' => 'Дата', 'type' => 'string'],
                        ]
                    ],
                ],
            ],
        ]
    ],
    [
        'displayName' => 'Источники',
        'methods' => [
            [
                'displayName' => 'Сводка',
                'name' => 'stat/sources/summary',
                'fields' => ['denial', 'visits', 'page_views', 'visits_delayed', 'visit_time', 'depth'],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'Нагрузка на сайт',
                        'fields' => []
                    ],
                ],
            ],
            [
                'displayName' => 'Сайты',
                'name' => 'stat/sources/sites',
                'fields' => ['denial', 'visits', 'page_views', 'visit_time', 'depth'],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'Сайты',
                        'fields' => [
                            [
                                'name' => 'url',
                                'displayName' => 'URL сайта-источника',
                                'type' => 'string'
                            ],
                        ]
                    ],
                ],
            ],
            [
                'displayName' => 'Поисковые системы',
                'name' => 'stat/sources/search_engines',
                'fields' => ['denial', 'visits', 'page_views', 'visit_time', 'depth'],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'Поисковые системы',
                        'fields' => [
                            [
                                'name' => 'name',
                                'displayName' => 'Название системы',
                                'type' => 'string'
                            ]
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'Поисковые фразы',
                'name' => 'stat/sources/phrases',
                'fields' => ['denial', 'visits', 'page_views', 'visit_time', 'depth'],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'Поисковые системы',
                        'fields' => [
                            [
                                'name' => 'phrase',
                                'displayName' => 'Фраза',
                                'type' => 'string'
                            ],
                            [
                                'name' => 'search_engines',
                                'displayName' => 'Фраза',
                                'type' => 'object',
                                'fields' => [
                                    [
                                        'name' => 'se_name',
                                        'displayName' => 'Имя поисковой системы',
                                        'type' => 'string'
                                    ],
                                    [
                                        'name' => 'se_page',
                                        'displayName' => 'Номер страницы результатов поиска',
                                        'type' => 'int'
                                    ],
                                    [
                                        'name' => 'se_url',
                                        'displayName' => 'Ссылка на поисковую систему',
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
        'displayName' => 'Содержание',
        'methods' => [
            [
                'displayName' => 'Популярное содержание',
                'name' => 'stat/content/popular',
                'fields' => [
                    'page_views',
                    'exit',
                    'entrance',
                ],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'Популярное содержание',
                        'fields' => [
                            [
                                'name' => 'url',
                                'displayName' => 'URL страницы',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'Страницы входа',
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
                        'displayName' => 'Страницы входа',
                        'fields' => [
                            [
                                'name' => 'url',
                                'displayName' => 'URL страницы сайта',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'Страницы выхода',
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
                        'displayName' => 'Страницы выхода',
                        'fields' => [
                            [
                                'name' => 'url',
                                'displayName' => 'URL страницы сайта',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'Заголовки страниц',
                'name' => 'stat/content/titles',
                'fields' => ['page_views'],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'Заголовки страниц',
                        'fields' => [
                            [
                                'name' => 'name',
                                'displayName' => 'Заголовок',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
        ]
    ],
    [
        'displayName' => 'Компьютеры',
        'methods' => [
            [
                'displayName' => 'Браузеры',
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
                        'displayName' => 'Браузеры',
                        'fields' => [
                            [
                                'name' => 'version',
                                'displayName' => 'Версия',
                                'type' => 'string'
                            ],
                            [
                                'name' => 'name',
                                'displayName' => 'Браузер',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'Операционные системы',
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
                        'displayName' => 'Операционные системы',
                        'fields' => [
                            [
                                'name' => 'name',
                                'displayName' => 'Операционная система',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'Мобильные устройства',
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
                        'displayName' => 'Мобильные устройства',
                        'fields' => [
                            [
                                'name' => 'name',
                                'displayName' => 'Название',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'Версии Flash',
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
                        'displayName' => 'Версии Flash',
                        'fields' => [
                            [
                                'name' => 'name',
                                'displayName' => 'Версия',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'Наличие JavaScript',
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
                        'displayName' => 'Наличие JavaScript',
                        'fields' => [
                            [
                                'name' => 'name',
                                'displayName' => 'Статус',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
        ]
    ],
    [
        'displayName' => 'География',
        'methods' => [
            [
                'displayName' => 'Отчет по Странам мира',
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
                        'displayName' => 'Отчет по Странам мира',
                        'fields' => [
                            [
                                'name' => 'region_type',
                                'displayName' => 'Тип региона',
                                'type' => 'string'
                            ],
                            [
                                'name' => 'name',
                                'displayName' => 'Регион',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
        ]
    ],
];
