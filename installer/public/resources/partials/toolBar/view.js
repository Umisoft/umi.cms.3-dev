define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.ToolBarView = Ember.View.extend({
            /**
             * @property layoutName
             */
            layoutName: 'toolBar',
            /**
             * Henjdsq контекст
             * @property rootContext
             */
            rootContext: null,
            /**
             * Контекст для которого применяется действие. В случае отсутствия контекста кнопки скрываются
             * В форме значение свойства равно rootContext
             * @property contextAction
             */
            contextAction: null,
            /**
             * View кнопки
             */
            buttonView: Ember.View.extend({
                tagName: 'li',
                isDropdownButton: function(){
                    var elementType = this.get('context.elementType');
                    return elementType === 'dropdownButton';
                }.property(),
                didInsertElement: function(){
                    var self = this;
                    if(self.get('isDropdownButton')){
                        self.$().click(function(){
                            $(this).find('.umi-toolbar-create-list').toggle();
                        });
                    }
                }
            })
        });
    };
});
