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
        'popup',
        'chartControl',
        'DS'
    ],
    function(UMI){
        'use strict';
        return function(){
             UMI.advanceReadiness();
        };
    }
);