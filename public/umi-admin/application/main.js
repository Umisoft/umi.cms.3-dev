define(
    [
        'App',
        'topBar',
        'divider',
        'dock',
        'toolbar',
        'tableControl',
        'fileManager',
        'treeSimple',
        'tree',
        'forms',
        'notification',
        'dialog',
        'popup',
        'DS',
        'table',
        'sideMenu'
    ],
    function(UMI){
        'use strict';
        return function(){
             UMI.advanceReadiness();
        };
    }
);