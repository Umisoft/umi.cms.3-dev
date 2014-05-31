define(['App'], function(UMI){
    "use strict";

    return function(){
        /**
         * Mixin реализующий интерфейс для контрола имеющего контекстное меню.
         * TODO: Инкапсулировать методы контекстного меню
         */
        UMI.ControlWithContextMenu = Ember.Mixin.create({
            selectAction: function(){
                var componentName = this.get('componentName');
                return UMI.Utils.LS.get(componentName + '.contextAction');
            }.property('componentName'),

            selectActionIcon: function(){
                if(this.get('selectAction')){
                    return 'icon-' + this.get('selectAction.behaviour.name');
                }
            }.property('selectAction'),

            actions: {
                toggleFastAction: function(action){
                    var selectAction;
                    var componentName = this.get('componentName');
                    if(!this.get('selectAction') || this.get('selectAction').behaviour !== action.behaviour){
                        selectAction = action;
                    } else{
                        selectAction = null;
                    }
                    this.set('selectAction', selectAction);
                    UMI.Utils.LS.set(componentName + '.contextAction', selectAction);
                }
            }
        });
    };
});
