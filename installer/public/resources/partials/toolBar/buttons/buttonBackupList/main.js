define(['App', 'text!./template.hbs'],
    function(UMI, template){
        "use strict";

        return function(){
            UMI.ButtonBackupListView = UMI.FormControlDropUpView.extend({
                tagName: 'div',
                template: Ember.Handlebars.compile(template)
            });
        };
    }
);