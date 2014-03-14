define(
    [
        'App',
        'topBar',
        'dock',
        'tableControl',
        'fileManager',
        'tree',
        'form',
        'search',
        'notification',
        'DS'
    ],
    function(UMI){
        'use strict';
        return function(){
             UMI.advanceReadiness();
        };
    }
);