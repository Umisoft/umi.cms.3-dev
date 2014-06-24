define(
    ['App', './elements/main', './formBase/main', './formControl/main'],
    function(UMI, elements, formBase, formControl){
        'use strict';

        elements();
        formBase();
        formControl();
    }
);