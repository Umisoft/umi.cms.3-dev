define(
    ['App', './formBase/main', './formControl/main'],
    function(UMI, formBase, formControl){
        'use strict';

        formBase();
        formControl();
    }
);