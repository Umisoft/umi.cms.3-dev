define(
    [
        'partials/toolbar/buttons/button/main',
        'partials/toolbar/buttons/dropdownButton/main',
        'partials/toolbar/buttons/splitButton/main',
        'partials/toolbar/buttons/contextMenu/main'
    ],
    function(button, dropdownButton, splitButton, contextMenu){
        "use strict";

        return function(){
            button();
            dropdownButton();
            splitButton();
            contextMenu();
        };
    }
);