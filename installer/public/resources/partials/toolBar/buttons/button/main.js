define(['App'],
    function(UMI){
        "use strict";

        return function(){
            UMI.ButtonView = Ember.View.extend({
                template: Ember.Handlebars.compile('<i class="icon icon-{{unbound type}}"></i>'),
                tagName: 'a',
                classNames: ['umi-button-icon-32', 'umi-light-bg'],
                attributeBindings: ['title'],
                title: function(){
                    return this.get('button.displayName');
                }.property(),
                click: function(){
                    this.get('controller').send('sendAction', this.get('button'), this.get('object'));
                }
            });
        };
    }
);