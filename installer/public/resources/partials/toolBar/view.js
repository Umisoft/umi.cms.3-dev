define(['App'], function(UMI){
    'use strict';

    return function(){

        UMI.ToolBarElementView = Ember.View.extend({
            tagName: 'li',

            template: function(){
                var type = this.get('context.type');
                return Ember.Handlebars.compile('{{view view.' + type + 'View meta=this}}');
            }.property(),

            buttonView: function(){
                var behaviour = this.get('context.behaviour.name');
                if(behaviour){
                    behaviour = UMI.buttonBehaviour.get(behaviour) || {};
                } else{
                    behaviour = {};
                }
                var instance = UMI.ButtonView.extend(behaviour);
                return instance;
            }.property(),

            dropdownButtonView: function(){
                var behaviour = this.get('context.behaviour.name');
                if(behaviour){
                    behaviour = UMI.dropdownButtonBehaviour.get(behaviour) || {};
                } else{
                    behaviour = {};
                }
                var instance = UMI.DropdownButtonView.extend(behaviour);
                return instance;
            }.property()
        });

        UMI.ToolBarView = Ember.View.extend({
            /**
             * @property layoutName
             */
            layoutName: 'toolBar',
            /**
             * @property classNames
             */
            classNames: ['s-unselectable', 'umi-toolbar'],

            elementView: UMI.ToolBarElementView
        });
    };
});
