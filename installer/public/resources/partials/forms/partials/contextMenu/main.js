define(['App', 'text!./contextMenu.hbs'], function(UMI, contextMenuTpl){
    "use strict";

    return function(){

        UMI.FormContextMenuView = Ember.View.extend({
            layout: Ember.Handlebars.compile(contextMenuTpl),
            tagName: 'ul',
            classNames: ['button-group', 'umi-form-control-buttons']
        });
    };
});
