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
        active: function(){
            var params = this.get('controller.target.router.state.params');
            if(params && 'settings.component' in params){
                return params['settings.component'].component === this.get('context.name');
            }
        }.property('controller.parentController.target.router.state.params'),
        actions: {
            expanded: function(){
                this.toggleProperty('isExpanded');
            }
        }
    });
});