define(['App'],
    function(UMI){
        "use strict";

        return function(){
            UMI.ButtonView = Ember.View.extend({
                tagName: 'a',

                templateName: 'partials/button',

                classNameBindings: 'meta.attributes.class',

                attributeBindings: ['title'],

                title: Ember.computed.alias('meta.attributes.title'),

                label: function(){
                    return this.get('meta.attributes.label');
                }.property('meta.attributes.label'),

                iconClass: function(){
                    return 'icon-' + this.get('meta.behaviour.name');
                }.property('meta.behaviour.name'),

                click: function(){
                    var behaviour = this.get('meta').behaviour;
                    var params = {
                        behaviour: behaviour
                    };
                    this.send(behaviour.name, params);
                }
            });

            function ButtonBehaviour(){}
            ButtonBehaviour.prototype = Object.create(UMI.globalBehaviour);
            UMI.buttonBehaviour = new ButtonBehaviour();
        };
    }
);