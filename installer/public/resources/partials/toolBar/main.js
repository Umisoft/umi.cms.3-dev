define(['App', 'text!./templates/toolBar.hbs'], function(UMI, toolBarTpl){
    'use strict';

    UMI.ToolBarView = Ember.View.extend({
        /**
         * @property layout
         */
        layout: Ember.Handlebars.compile(toolBarTpl)
    });
});