<?php

return [
    'traffic' => [
        'resources' => [
            [
                'name' => 'stat/traffic/summary',
                'reports' => [
                    [
                        'name' => 'data',
                        'graph' => [
                            'type' => 'line',
                            'axisX' => 'date',
                            'axisY' => 'visits'
                        ]
                    ],
                ]
            ],
            [
                'name' => 'stat/traffic/deepness',
                'reports' => [
                    [
                        'name' => 'data_depth',
                        'graph' => [
                            'type' => 'line',
                            'axisX' => 'name',
                            'axisY' => 'visits'
                        ]
                    ],
                    [
                        'name' => 'data_time',
                        'graph' => [
                            'type' => 'line',
                            'axisX' => 'name',
                            'axisY' => 'visits'
                        ]
                    ]
                ],
            ],
            [
                'name' => 'stat/traffic/hourly',
                'reports' => [
                    [
                        'name' => 'data',
                        'graph' => [
                            'type' => 'line',
                            'axisX' => 'hours',
                            'axisY' => 'visit_time'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'stat/traffic/load',
                'reports' => [
                    [
                        'name' => 'data',
                        'graph' => [
                            'type' => 'line',
                            'axisX' => 'max_rps_date',
                            'axisY' => 'max_users'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'sources' => [
        'resources' => [
            [
                'name' => 'stat/sources/summary',
                'reports' => [
                    [
                        'name' => 'data',
                        'graph' => [
                            'type' => 'bar',
                            'axisX' => 'name',
                            'axisY' => 'visits'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'stat/sources/sites',
                'reports' => [
                    [
                        'name' => 'data'
                    ]
                ]
            ],
            [
                'name' => 'stat/sources/search_engines',
                'reports' => [
                    [
                        'name' => 'data',
                        'graph' => [
                            'type' => 'bar',
                            'axisX' => 'name',
                            'axisY' => 'visits'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'stat/sources/phrases',
                'reports' => [
                    [
                        'name' => 'data'
                    ]
                ]
            ]
        ]
    ],
    'content' => [
        'resources' => [
            [
                'name' => 'stat/content/popular',
                'reports' => [
                    [
                        'name' => 'data'
                    ]
                ]
            ],
            [
                'name' => 'stat/content/entrance',
                'reports' => [
                    [
                        'name' => 'data'
                    ]
                ]
            ],
            [
                'name' => 'stat/content/exit',
                'reports' => [
                    [
                        'name' => 'data'
                    ]
                ]
            ],
            [
                'name' => 'stat/content/titles',
                'reports' => [
                    [
                        'name' => 'data'
                    ]
                ]
            ]
        ]
    ],
    'computers' => [
        'resources' => [
            [
                'name' => 'stat/tech/browsers',
                'reports' => [
                    [
                        'name' => 'data',
                        'graph' => [
                            'type' => 'bar',
                            'axisX' => [
                                'name',
                                'version'
                            ],
                            'axisY' => 'visits'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'stat/tech/os',
                'reports' => [
                    [
                        'name' => 'data',
                        'graph' => [
                            'type' => 'bar',
                            'axisX' => 'name',
                            'axisY' => 'visits'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'stat/tech/mobile',
                'reports' => [
                    [
                        'name' => 'data',
                        'graph' => [
                            'type' => 'bar',
                            'axisX' => 'name',
                            'axisY' => 'visits'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'stat/tech/flash',
                'reports' => [
                    [
                        'name' => 'data',
                        'graph' => [
                            'type' => 'bar',
                            'axisX' => 'name',
                            'axisY' => 'visits'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'stat/tech/javascript',
                'reports' => [
                    [
                        'name' => 'data',
                        'graph' => [
                            'type' => 'bar',
                            'axisX' => 'name',
                            'axisY' => 'visits'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'geo' => [
        'resources' => [
            [
                'name' => 'stat/geo',
                'reports' => [
                    [
                        'name' => 'data',
                        'graph' => [
                            'type' => 'bar',
                            'axisX' => 'name',
                            'axisY' => 'visits'
                        ]
                    ]
                ]
            ]
        ]
    ]
];
