define(
    [
        'App',

        //Вызов partials
        'topBar',
        'dock',
        'toolBar',
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