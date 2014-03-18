<?php
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
                    [
                        'name' => 'data',
                        'title' => 'Посещаемость',
                        'fields' => [
                            ['name' => 'wday', 'title' => 'День недели', 'type' => 'int'], // 0-6
                        ]
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
