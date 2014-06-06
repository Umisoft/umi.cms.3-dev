define(
    [
        'partials/toolbar/buttons/globalBehaviour',
        'partials/toolbar/buttons/button/main',
        'partials/toolbar/buttons/dropdownButton/main',
        'partials/toolbar/buttons/splitButton/main',
        'partials/toolbar/buttons/contextMenu/main'
    ],
    function(globalBehaviour, button, dropdownButton, splitButton, contextMenu){
        "use strict";

        return function(){
            globalBehaviour();
            button();
            dropdownButton();
            splitButton();
            contextMenu();
        };
    }
);