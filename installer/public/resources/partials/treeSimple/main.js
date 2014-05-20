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
        isExpanded: true,
        checkExpanded: function(){
            var params = this.get('controller.target.router.state.params');
            if(params && 'settings.component' in params && params['settings.component'].component === this.get('context.name')){
                this.set('isExpanded', true);
            }
        },
        nestedSlug: function(){
            var computedSlug = '';
            if(this.get('parentView').constructor.toString() === '.TreeSimpleItemView'){
                computedSlug = this.get('parentView').get('context.name') + '.';
            }
            computedSlug += this.get('context.name');
            return computedSlug;
        }.property(),
        actions: {
            expanded: function(){
                this.toggleProperty('isExpanded');
            }
        }
    });
});