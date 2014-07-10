define(
    [
        'App',
        './controllers',
        './views'
    ],
    function(UMI, controllers, views){
        'use strict';

        controllers();
        views();
    }
);