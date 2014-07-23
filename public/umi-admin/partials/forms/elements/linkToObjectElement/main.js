define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.LinkToObjectElementView = Ember.View.extend({
            templateName: 'partials/linkToObjectElement',
            classNames: ['row', 'collapse'],
            popupParams: function(){
                return {
                    object: this.get('object'),
                    meta: this.get('meta')
                };
            }.property()
        });


        UMI.LinkToObjectLayoutController = Ember.Controller.extend({
            sideBarControl: true,
            objects: [
                {id: 'structure', displayName: 'Структура'},
                {id: 'structure', displayName: 'Структура'},
                {id: 'structure', displayName: 'Структура'}
            ],
            tableControlSettings: function(){
                return {
                    control: {
                        collectionName: 'structure'
                    }
                };
            }.property()
        });

        UMI.LinkToObjectLayoutView = Ember.View.extend({
            classNames: ['s-full-height'],
            templateName: 'partials/linkToObjectLayout'
        });
    };
});