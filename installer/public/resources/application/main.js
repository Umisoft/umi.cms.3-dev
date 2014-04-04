define(
    [
        'App',

        //Вызов partials
        'topBar',
        'dock',
        'tableControl',
        'fileManager',
        'tree',
        'form',
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