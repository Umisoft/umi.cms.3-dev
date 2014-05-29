define(
    [
        'partials/toolbar/buttons/button/main',
        'partials/toolbar/buttons/dropDownButton/main',
        'partials/toolbar/buttons/buttonWithActive/main',
        'partials/toolbar/buttons/buttonBackupList/main',
        'partials/toolbar/buttons/saveButton/main'
    ],
    function(button, dropDownButton, buttonWithActive, buttonBackupList, saveButton){
        "use strict";

        return function(){
            button();
            dropDownButton();
            buttonWithActive();
            buttonBackupList();
            saveButton();
        };
    }
);