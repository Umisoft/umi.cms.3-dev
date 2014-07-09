define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.ImageElementView = Ember.View.extend({
            template: 'partials/imageElement',

            classNames: ['row', 'collapse'],

            tumb: null,//TODO: Нужно превью? Если да, то предстоит генерить его на фронте

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