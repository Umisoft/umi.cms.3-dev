define([
    'App',
    'text!./list.hbs',
    'text!./item.hbs'
], function(UMI, listTpl, itemTpl){
    'use strict';

    UMI.TreeSimpleView = Ember.View.extend({
        classNames: ['row', 's-full-height'],
        template: Ember.Handlebars.compile(listTpl)
    });

    UMI.TreeSimpleItemView = Ember.View.extend({
        tagName: 'li',
        template: Ember.Handlebars.compile(itemTpl),
        isExpanded: false,
        actions: {
            expanded: function(){
                this.toggleProperty('isExpanded');
            }
        }
    });
});