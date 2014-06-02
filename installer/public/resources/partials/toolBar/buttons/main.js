define(
    [
        'partials/toolbar/buttons/button/main',
        'partials/toolbar/buttons/dropdownButton/main',
        'partials/toolbar/buttons/contextMenu/main'
    ],
    function(button, dropdownButton, contextMenu){
        "use strict";

        return function(){
            button();
            dropdownButton();
            contextMenu();
        };
    }
);