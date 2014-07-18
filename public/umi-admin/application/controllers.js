define([], function(){
    'use strict';
    return function(UMI){
        UMI.ApplicationController = Ember.ObjectController.extend({
            settings: null,
            modules: null
        });

        /**
         * @class ComponentController
         * @extends Ember.ObjectController
         */
        UMI.ComponentController = Ember.ObjectController.extend({
            /**
             * Уникальное имя компонента
             * @property name
             */
            name: function(){
                return this.get('container').lookup('route:module').get('context.name') + Ember.String.capitalize(this.get('model.name'));
            }.property('model.name'),

            settings: null,

            dataSourceBinding: 'settings.dataSource',

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
             @return Array Возвращает массив Ember объектов содержащий возможные действия текущего контрола
             */
            contentControls: function(){
                var self = this;
                var contentControls = [];
                var settings = this.get('settings');
                try{

                    var selectedContext = this.get('selectedContext') === 'root' ? 'emptyContext' : 'selectedContext';
                    var controls = settings.contents[selectedContext];
                    var key;
                    var control;
                    for(key in controls){ //for empty - createForm & filter
                        if(controls.hasOwnProperty(key)){
                            control = controls[key];
                            control.id = key;// used by router
                            control.name = key;// used by templates
                            contentControls.push(control);
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
                    if(settings && settings.hasOwnProperty('sideBar')){
                        var control;
                        var controlParams;
                        for(control in settings.sideBar){
                            if(settings.sideBar.hasOwnProperty(control)){
                                controlParams = settings.sideBar[control];
                                if(Ember.typeOf(controlParams) !== 'object'){
                                    controlParams = {};
                                }
                                sideBarControl = controlParams;
                                sideBarControl.name = control;
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

                return sideBarControl;
            }.property('settings')
        });

        UMI.ContextController = Ember.ObjectController.extend({});

        UMI.ActionController = Ember.ObjectController.extend({
            queryParams: ['type'],
            type: null
        });
    };
});