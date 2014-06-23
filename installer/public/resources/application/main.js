define(
    [
        'App',

        //Вызов elements
        'offcanvas',

        //Вызов partials
        'topBar',
        'dock',
        'toolbar',
        'tableControl',
        'fileManager',
        'treeSimple',
        'tree',
        'forms',
        'search',
        'notification',
        'dialog',
        'popup',
        'chartControl',
        'DS',
        'table',
        'accordion',
        'megaIndex',
        'yandexWebmaster'
    ],
    function(UMI){
        'use strict';
        return function(){
             UMI.advanceReadiness();
        };
    }
);