define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.ToolBarView = Ember.View.extend({
            /**
             * @property layoutName
             */
            layoutName: 'toolBar',
            /**
             * @property classNames
             */
            classNames: ['s-unselectable', 'umi-toolbar'],
            /**
             * Root контекст
             * @property rootContext
             */
            rootContext: null,
            /**
             * Контекст для которого применяется действие. В случае отсутствия контекста кнопки скрываются
             * В форме значение свойства равно rootContext
             * @property contextAction
             */
            contextAction: null
        });

        /**
         * View кнопки
         */
        UMI.ToolBarButtonView = Ember.View.extend({
            tagName: 'li',

            /**
             * Root контекст
             * @property rootContext
             */
            rootContext: null,
            /**
             * Контекст для которого применяется действие. В случае отсутствия контекста кнопки скрываются
             * В форме значение свойства равно rootContext
             * @property contextAction
             */
            contextAction: null,

            template: function(){
                var template;
                var type = this.get('context.type') || '';
                try{
                    template = this.get(Ember.String.camelize(type) + 'Template') || '';
                    if(!template){
                        throw new Error('Для кнопки с типом ' + type + ' не реализован шаблонный метод.');
                    }
                } catch(error){
                    this.get('controller').send('backgroundError', error);// TODO: при первой загрузке сообщения не всплывают.
                } finally{
                    return Ember.Handlebars.compile(template);
                }
            }.property(),

            dropDownButtonTemplate: function(){
                return '{{view "dropDownButton" button=this object=view.rootContext}}';
            }.property(),

            buttonTemplate: function(){
                return '{{view "button" button=this object=view.contextAction}}';
            }.property(),

            buttonSwitchActivityTemplate: function(){
                return '{{view "buttonSwitchActivity" button=this object=view.contextAction}}';
            }.property(),

            buttonBackupListTemplate: function(){
                return '{{view "buttonBackupList" button=this object=view.contextAction}}';
            }.property(),

            saveButtonTemplate: function(){
                return '{{view "saveButton" button=this object=view.contextAction}}';
            }.property(),

            submitButtonTemplate: function(){
                return '{{view "submitButton" button=this object=view.contextAction}}';
            }.property()
        });
    };
});
