define(
    [
        'App',
        'topBar',
        'dock',
        'tableControl',
        'tree',
        'form',
        'search',
        'DS'
    ],
    function(UMI){
        'use strict';
        return function(){
             UMI.advanceReadiness();
        };
    }
);