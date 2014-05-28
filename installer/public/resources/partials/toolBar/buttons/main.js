define(
    [
        'partials/toolbar/buttons/button/main',
        'partials/toolbar/buttons/dropDownButton/main',
        'partials/toolbar/buttons/ButtonWithActive/main'
    ],
    function(button, dropDownButton, buttonWithActive){
        "use strict";

        return function(){
            button();
            dropDownButton();
            buttonWithActive();
        };
    }
);