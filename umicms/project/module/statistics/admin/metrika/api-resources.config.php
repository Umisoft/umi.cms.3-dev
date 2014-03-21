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
                        'displayName' => 'component:metrika:deepnesDatadepth',
                        'fields' => []
                    ],
                    [
                        'name' => 'data_time',
                        'displayName' => 'component:metrika:deepnesDatatime',
                        'fields' => [
                            [
                                'name' => 'name',
                                'displayName' => 'component:metrika:deepnesLong',
                                'type' => 'string'
                            ],
                        ]
                    ],
                ],
            ],
            [
                'displayName' => 'component:metrika:trafficHourly',
                'name' => 'stat/traffic/hourly',
                'fields' => [
                    ['denial', 'avg_visits', 'depth', 'visit_time'],
                ],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'component:metrika:trafficHourlyReport',
                        'fields' => [
                            [
                                'name' => 'hours',
                                'displayName' => 'component:metrika:trafficHourlyTime',
                                'type' => 'string'
                            ],
                        ]
                    ],
                ],
            ],
            [
                'displayName' => 'component:metrika:trafficLoad',
                'name' => 'stat/traffic/load',
                'fields' => [
                    ['max_rps', 'max_users'],
                ],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'component:metrika:trafficLoadReport',
                        'fields' => [
                            [
                                'name' => 'max_rps_time',
                                'displayName' => 'component:metrika:trafficLoadMaxRpsTime',
                                'type' => 'string'
                            ],
                            [
                                'name' => 'max_rps_date',
                                'displayName' => 'component:metrika:trafficLoadMaxRpsDate',
                                'type' => 'string'
                            ],
                            [
                                'name' => 'max_users_date',
                                'displayName' => 'component:metrika:trafficLoadMaxUsersDate',
                                'type' => 'string'
                            ],
                            [
                                'name' => 'max_users_time',
                                'displayName' => 'component:metrika:trafficLoadMaxUsersTime',
                                'type' => 'string'
                            ],
                            [
                                'name' => 'date',
                                'displayName' => 'component:metrika:trafficLoadReportDate',
                                'type' => 'string'
                            ],
                        ]
                    ],
                ],
            ],
        ]
    ],
    [
        'displayName' => 'component:metrika:sources',
        'methods' => [
            [
                'displayName' => 'component:metrika:sourcesSummary',
                'name' => 'stat/sources/summary',
                'fields' => ['denial', 'visits', 'page_views', 'visits_delayed', 'visit_time', 'depth'],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'component:metrika:sourcesSummaryReport',
                        'fields' => []
                    ],
                ],
            ],
            [
                'displayName' => 'component:metrika:sourcesSites',
                'name' => 'stat/sources/sites',
                'fields' => ['denial', 'visits', 'page_views', 'visit_time', 'depth'],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'component:metrika:sourcesSitesReport',
                        'fields' => [
                            [
                                'name' => 'url',
                                'displayName' => 'component:metrika:sourcesSitesReportUrl',
                                'type' => 'string'
                            ],
                        ]
                    ],
                ],
            ],
            [
                'displayName' => 'component:metrika:sourcesSearchEngines',
                'name' => 'stat/sources/search_engines',
                'fields' => ['denial', 'visits', 'page_views', 'visit_time', 'depth'],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'component:metrika:sourcesSearchEnginesReport',
                        'fields' => [
                            [
                                'name' => 'name',
                                'displayName' => 'component:metrika:sourcesSearchEngineName',
                                'type' => 'string'
                            ]
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'component:metrika:sourcesPhrase',
                'name' => 'stat/sources/phrases',
                'fields' => ['denial', 'visits', 'page_views', 'visit_time', 'depth'],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'component:metrika:sourcesPhraseReport',
                        'fields' => [
                            [
                                'name' => 'phrase',
                                'displayName' => 'component:metrika:sourcesPhraseReportPhrase',
                                'type' => 'string'
                            ],
                            [
                                'name' => 'search_engines',
                                'displayName' => 'component:metrika:sourcesPhraseReportSearchEngine',
                                'type' => 'object',
                                'fields' => [
                                    [
                                        'name' => 'se_name',
                                        'displayName' => 'component:metrika:sourcesSeName',
                                        'type' => 'string'
                                    ],
                                    [
                                        'name' => 'se_page',
                                        'displayName' => 'component:metrika:sourcesSePage',
                                        'type' => 'int'
                                    ],
                                    [
                                        'name' => 'se_url',
                                        'displayName' => 'component:metrika:sourcesSeUrl',
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
        'displayName' => 'component:metrika:content',
        'methods' => [
            [
                'displayName' => 'component:metrika:contentPopular',
                'name' => 'stat/content/popular',
                'fields' => [
                    'page_views',
                    'exit',
                    'entrance',
                ],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'component:metrika:contentReportName',
                        'fields' => [
                            [
                                'name' => 'url',
                                'displayName' => 'component:metrika:contentReportUrl',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'component:metrika:contentEntrance',
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
                        'displayName' => 'component:metrika:contentEntranceReport',
                        'fields' => [
                            [
                                'name' => 'url',
                                'displayName' => 'component:metrika:contentEntranceReportUrl',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'component:metrika:contentExit',
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
                        'displayName' => 'component:metrika:contentExitReport',
                        'fields' => [
                            [
                                'name' => 'url',
                                'displayName' => 'component:metrika:contentExitReportUrl',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'component:metrika:contentTitles',
                'name' => 'stat/content/titles',
                'fields' => ['page_views'],
                'reports' => [
                    [
                        'name' => 'data',
                        'displayName' => 'component:metrika:contentTitlesReport',
                        'fields' => [
                            [
                                'name' => 'name',
                                'displayName' => 'component:metrika:contentTitlesReportTitle',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
        ]
    ],
    [
        'displayName' => 'component:metrika:computers',
        'methods' => [
            [
                'displayName' => 'component:metrika:techBrowser',
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
                        'displayName' => 'component:metrika:techBrowserReport',
                        'fields' => [
                            [
                                'name' => 'version',
                                'displayName' => 'component:metrika:techBrowserReportVersion',
                                'type' => 'string'
                            ],
                            [
                                'name' => 'name',
                                'displayName' => 'component:metrika:techBrowserReportName',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'component:metrika:techOs',
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
                        'displayName' => 'component:metrika:techOsReport',
                        'fields' => [
                            [
                                'name' => 'name',
                                'displayName' => 'component:metrika:techOsReportName',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'component:metrika:techMobile',
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
                        'displayName' => 'component:metrika:techMobileReport',
                        'fields' => [
                            [
                                'name' => 'name',
                                'displayName' => 'component:metrika:techMobileReportName',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'component:metrika:techFlash',
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
                        'displayName' => 'component:metrika:techFlashReport',
                        'fields' => [
                            [
                                'name' => 'name',
                                'displayName' => 'component:metrika:techFlashReportName',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
            [
                'displayName' => 'component:metrika:techJavascript',
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
                        'displayName' => 'component:metrika:techJavascriptReport',
                        'fields' => [
                            [
                                'name' => 'name',
                                'displayName' => 'component:metrika:techJavascriptReportName',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
        ]
    ],
    [
        'displayName' => 'component:metrika:geo',
        'methods' => [
            [
                'displayName' => 'component:metrika:statGeo',
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
                        'displayName' => 'component:metrika:statGeoReport',
                        'fields' => [
                            [
                                'name' => 'region_type',
                                'displayName' => 'component:metrika:statGeoReportRegionType',
                                'type' => 'string'
                            ],
                            [
                                'name' => 'name',
                                'displayName' => 'component:metrika:statGeoReportRegion',
                                'type' => 'string'
                            ],
                        ],
                    ]
                ],
            ],
        ]
    ],
];
