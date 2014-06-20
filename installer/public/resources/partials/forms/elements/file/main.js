define(['App', 'text!./fileElement.hbs'], function(UMI, fileElement){
    "use strict";

    return function(){
        UMI.FileElementView = Ember.View.extend({
            template: Ember.Handlebars.compile(fileElement),

            classNames: ['umi-element', 'umi-element-file'],

            actions: {
                clearValue: function(){
                    var self = this;
                    var el = self.$();
                    if(Ember.typeOf(self.get('object')) === 'instance'){
                        var dataSource = self.get('meta.dataSource');
                        self.get('object').set(dataSource, '');
                    } else{
                        el.find('input').val('');
                    }
                }
            }
        });
    };
});