define(['App'],
    function(UMI){
        "use strict";

        return function(){
            UMI.SaveButtonView = Ember.View.extend({
                tagName: 'a',
                template: Ember.Handlebars.compile('{{view.button.displayName}}'),
                classNames: ['s-margin-clear', 'umi-button', 'wide'],

                classNameBindings: ['object.isDirty::umi-disabled', 'object.isValid::umi-disabled'],

                click: function(){
                    var model = this.get('object');
                    if(model.get('isDirty') && model.get('isValid')){
                        var button = this.$();
                        button.addClass('loading');
                        var params = {
                            object: model,
                            handler: button[0]
                        };
                        this.get('controller').send('save', params);
                    }
                }
            });
        };
    }
);