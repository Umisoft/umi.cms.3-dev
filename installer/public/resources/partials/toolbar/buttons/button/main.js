define(['App'],
    function(UMI){
        "use strict";

        return function(){
            UMI.ButtonView = Ember.View.extend({
                label: function(){
                    return this.get('meta.attributes.label');
                }.property('meta.attributes.label'),
                templateName: 'partials/button',
                tagName: 'a',
                classNameBindings: 'meta.attributes.class',
                attributeBindings: ['title'],
                title: Ember.computed.alias('meta.attributes.title'),
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