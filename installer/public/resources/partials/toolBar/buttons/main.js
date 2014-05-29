define(
    [
        'partials/toolbar/buttons/button/main',
        'partials/toolbar/buttons/dropDownButton/main',
        'partials/toolbar/buttons/buttonWithActive/main',
        'partials/toolbar/buttons/buttonBackupList/main',
        'partials/toolbar/buttons/saveButton/main',
        'partials/toolbar/buttons/submitButton/main'
    ],
    function(button, dropDownButton, buttonWithActive, buttonBackupList, saveButton, submitButton){
        "use strict";

        return function(){
            button();
            dropDownButton();
            buttonWithActive();
            buttonBackupList();
            saveButton();
            submitButton();
        };
    }
);