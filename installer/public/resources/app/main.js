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
        'dialog',
        'DS'
    ],
    function(UMI){
        'use strict';
        return function(){
             UMI.advanceReadiness();
        };
    }
);