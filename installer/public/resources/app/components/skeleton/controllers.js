define([], function(){
    'use strict';
    return function(UMI){
        /**
         * @class ComponentController
         * @extends Ember.ObjectController
         */
        UMI.ComponentController = Ember.ObjectController.extend({
            /**
             Имеет компонент дерево или нет
             @property hasTree
             @type Boolean
             @default false
             */
            hasTree: false,
            /**
             Массив view конторолов для компонента
             @property controls
             @type Array
             @default null
             @example
                 [
                    {"name":"tree","displayName":"Новостные рубрики"},
                    {"name":"filter","displayName":"Фильтр"},
                    {"name":"children","displayName":"Дочерние рубрики"},
                    {"name":"form","displayName":"Редактирование"}
                 ]
             */
            controls: null,
            /**
             Обьект содержащий имена view контролов для emptyContext и selectedContext
             @property context
             @type Object
             @default null
             @example
                    {
                        "emptyContext":{
                            "tree":{"controls":["tree"]},
                            "content":{"controls":["filter","children"]}
                        },
                        "selectedContext":{
                            "tree":{"controls":["tree"],
                            "actions":["delete","changeActivity"]},
                            "content":{"controls":["form","children"]
                        }
                   }
          */
            context: null,
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
                var allControls = this.get('controls');
                var context = this.get('context');
                var selectedContext  = this.get('selectedContext') === 'root' ? 'emptyContext' : 'selectedContext';
                var controls = context[selectedContext].content.controls;
                var contentControls = [];
                for(var i = 0; i < controls.length; i++){
                    contentControls.push(Ember.Object.create(allControls.findBy('name', controls[i])));
                }
                return contentControls;
            }.property('context', 'selectedContext'),
            searchQuery: null,
            actions: {
                search: function(searchQuery){
                    if(searchQuery){
                        this.transitionToRoute('component.search', searchQuery);
                    }
                }
            }
        });
    };
});