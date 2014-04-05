define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.TableControlController = Ember.ObjectController.extend({
            objects: function(){
                var children = this.get('model.object.children');
                console.log(children);
                return children;
            }.property('model.object.children')
        });
    };
});