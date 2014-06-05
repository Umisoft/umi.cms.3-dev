define(['App'], function(UMI){
    'use strict';

    return function(){

        UMI.ToolBarElementView = Ember.View.extend({
            tagName: 'li',

            template: function(){
                var self = this;
                var type = this.get('context.type');
                if(this.get(type + 'View')){
                    return Ember.Handlebars.compile('{{view view.' + type + 'View meta=this}}');
                } else{
                    try{
                        throw new Error('View c типом ' + type + ' не зарегестрирован для toolbar контроллера');
                    } catch(error){
                        Ember.run.next(function(){
                            self.get('controller').send('backgroundError', error);
                        });
                    }
                }
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
            }.property(),

            splitButtonView: function(){
                var behaviour = this.get('context.behaviour.name');
                if(behaviour){
                    behaviour = UMI.splitButtonBehaviour.get(behaviour) || {};
                } else{
                    behaviour = {};
                }
                var instance = UMI.SplitButtonView.extend(behaviour);
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
