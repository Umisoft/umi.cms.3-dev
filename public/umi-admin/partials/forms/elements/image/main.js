define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.ImageElementView = Ember.View.extend({
            template: 'partials/imageElement',

            classNames: ['row', 'collapse'],

            tumb: null,//TODO: Нужно превью? Если да, то предстоит генерить его на фронте

            popupParams: function(){
                return {
                    templateParams: {
                        object: this.get('object'),
                        meta: this.get('meta')
                    },

                    viewParams: {
                        popupType: 'fileManager'
                    }
                };
            }.property(),

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
                },

                showPopup: function(params){
                    var self = this;
                    var object = self.get('object');
                    var property = this.get('meta.dataSource');
                    object.clearValidateForProperty(property);
                    this.get('controller').send('showPopup', params);
                }
            }
        });
    };
});