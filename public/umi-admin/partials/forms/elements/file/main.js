define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.FileElementView = Ember.View.extend({
            templateName: 'partials/fileElement',

            classNames: ['row', 'collapse'],

            popupParams: function(){
                return {
                    templateParams: {
                        object: this.get('object'),
                        meta: this.get('meta'),
                        fileSelect: function(fileInfo){
                            var self = this;
                            var templateParams = this.get('templateParams');
                            templateParams.get('object').set(templateParams.get('meta.dataSource'), Ember.get(fileInfo, 'path'));
                            self.get('controller').send('closePopup');
                        }
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