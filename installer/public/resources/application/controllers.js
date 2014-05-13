define([], function(){
    'use strict';
    return function(UMI){
        UMI.ApplicationController = Ember.ObjectController.extend({
            settings: null,
            settingsAllowed: function(){
                return 'baseSettingsURL' in window.UmiSettings;
            }.property()
        });

        /**
         * @class ComponentController
         * @extends Ember.ObjectController
         */
        UMI.ComponentController = Ember.ObjectController.extend({
            collectionName: function(){
                var settings = this.get('settings');
                return settings.collectionName;
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
                try{
                    var selectedContext = this.get('selectedContext') === 'root' ? 'emptyContext' : 'selectedContext';
                    var controls = settings.layout.contents[selectedContext];
                    var key;
                    var control;
                    for(key in controls){
                        if(controls.hasOwnProperty(key)){
                            control = controls[key];
                            control.name = key;
                            contentControls.push(Ember.Object.create(control));
                        }
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

                return contentControls;
            }.property('settings', 'selectedContext'),

//            showSideBar: false,

            /**
             Контрол компонента в области сайд бара
             @property sideBarControl
             @type Boolean
             @default false
             */
            sideBarControl: function(){
                var sideBarControl;
                var self = this;
                try{
                    var settings = this.get('settings');
                    if(settings && settings.layout.hasOwnProperty('sideBar')){
                        var control;
                        for(control in settings.layout.sideBar){
                            if(settings.layout.sideBar.hasOwnProperty(control)){
                                sideBarControl = settings.layout.sideBar[control];
                                sideBarControl.name = control;
                                sideBarControl = Ember.Object.create(sideBarControl);
                            }
                        }
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
//                this.set('showSideBar', sideBarControl);
                return sideBarControl;
            }.property('settings')
        });

        UMI.ContextController = Ember.ObjectController.extend({});

        UMI.ActionController = Ember.ObjectController.extend({
            queryParams: ['typeName'],
            typeName: null
        });
    };
});