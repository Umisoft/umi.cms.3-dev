define([], function(){
    'use strict';
    return function(UMI){
        UMI.ApplicationController = Ember.ObjectController.extend({
            settings: null
        });

        /**
         * @class ComponentController
         * @extends Ember.ObjectController
         */
        UMI.ComponentController = Ember.ObjectController.extend({
            collectionName: function(){
                var settings = this.get('settings');
                if(settings){
                    return settings.layout.collection;
                }
            }.property('settings'),
            settings: null,
            /**
             Выбранный контекcт, соответствующий модели роута 'Context'
             @property selectedContext
             @type String
             @default null
             */
            selectedContext: null,
            /**
             Вычисляемое свойсво возвращающее массив контролов для текущего контекста
             @method contentControls
             @return Array Массив
             */
            contentControls: function(){
                var self = this;
                var contentControls = [];
                var settings = this.get('settings');
                if(settings){
                    try{
                        var selectedContext = this.get('selectedContext') === 'root' ? 'emptyContext' : 'selectedContext';
                        var controls = settings.layout[selectedContext].contents.controls;
                        var control;
                        for(var i = 0; i < controls.length; i++){
                            control = settings.controls[controls[i]];
                            control.name = controls[i];
                            contentControls.push(Ember.Object.create(control));
                        }
                    } catch(error){
                        var errorObject = {
                            'statusText': error.name,
                            'message': error.message,
                            'stack': error.stack
                        };
                        Ember.run.next(function(){
                            self.send('templateLogs', errorObject, 'component');
                        });
                    }
                }
                return contentControls;
            }.property('settings', 'selectedContext'),
            /**
             Контрол компонента в области сайд бара
             @property sideBarControl
             @type Boolean
             @default false
             */
            sideBarControl: function(){
                var sideBarControl;
                var settings = this.get('settings');
                if(settings && settings.layout.emptyContext.hasOwnProperty('sideBar')){
                    var control = settings.layout.emptyContext.sideBar.controls[0];
                    sideBarControl = settings.controls[control];
                    sideBarControl.name = control;
                    sideBarControl = Ember.Object.create(sideBarControl);
                }
                return sideBarControl;
            }.property('settings')
        });

        UMI.ContextController = Ember.ObjectController.extend({});
    };
});