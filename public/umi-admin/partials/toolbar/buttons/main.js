define(
    [
        'partials/toolbar/buttons/globalBehaviour',
        'partials/toolbar/buttons/button/main',
        'partials/toolbar/buttons/dropdownButton/main',
        'partials/toolbar/buttons/splitButton/main'
    ],
    function(globalBehaviour, button, dropdownButton, splitButton){
        "use strict";

        return function(){
            globalBehaviour();
            button();
            dropdownButton();
            splitButton();
        };
    }
);