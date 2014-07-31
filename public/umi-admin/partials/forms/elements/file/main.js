define(['App'], function(UMI){
    "use strict";

    return function(){

        UMI.FileElementView = Ember.View.extend({
            templateName: 'partials/fileElement',

            classNames: ['row', 'collapse'],

            value: null,

            popupParams: function(){
                return {
                    viewParams: {
                        popupType: 'fileManager',
                        title: UMI.i18n.getTranslate('Select file')
                    },

                    templateParams: {
                        object: this.get('object'),
                        meta: this.get('meta'),
                        fileSelect: function(fileInfo){
                            var self = this;
                            var object = self.get('object');
                            var image = Ember.get(fileInfo, 'url') || '';
                            var baseUrl = Ember.get(window, 'UmiSettings.projectAssetsUrl');
                            var pattern = new RegExp('^' + baseUrl, 'g');
                            object.set(self.get('meta.dataSource'), image.replace(pattern, ''));
                            self.get('controller').send('closePopup');
                        }
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
            },

            init: function(){
                this._super();
                var self = this;
                var object = this.get('object');
                if(Ember.typeOf(object) === 'instance'){
                    var dataSource = self.get('meta.dataSource');
                    this.set('value', object.get(dataSource));
                    self.addObserver('object.' + dataSource, function(){
                        Ember.run.next(self, function(){
                            this.set('value', object.get(dataSource));
                        });
                    });
                }
            },

            willDestroyElement: function(){
                var self = this;
                var object = this.get('object');
                if(Ember.typeOf(object) === 'instance'){
                    var dataSource = self.get('meta.dataSource');
                    self.removeObserver('object.' + dataSource);
                }
            }
        });
    };
});