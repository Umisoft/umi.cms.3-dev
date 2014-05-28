define(
    [
        'partials/toolbar/buttons/button/main',
        'partials/toolbar/buttons/dropDownButton/main'
    ],
    function(button, dropDownButton){
        "use strict";

        return function(){
            button();
            dropDownButton();
        };
    }
);