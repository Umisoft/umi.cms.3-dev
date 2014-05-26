define(['App', 'text!./imageElement.hbs'], function(UMI, imageElement){
    "use strict";

    Ember.TEMPLATES['UMI/components/image-element'] = Ember.Handlebars.compile(imageElement);

    return function(){
        UMI.ImageElementComponent = Ember.Component.extend(UMI.InputValidate, {
            classNames: ['umi-element', 'umi-element-image'],

            tumb: '/files/.tmb/l1_aW1hZ2VzXG1hcmt1cFx1bWktMzAtY29sb3IucG5n1397205000.png',

            didInsertElement: function(){
                var el = this.$();
                el.find('.icon-delete').click(function(){
                    el.find('input').val('');
                });
            },

            actionForSend: 'showPopup', //Ультра хак без которого не будет работать sendAcrion - он не принимает имя напрямую, ему нужно отдавать перемнную с именем
            actions: {
                showPopup: function(popupType, object, meta){
                    this.sendAction('actionForSend', popupType, object, meta);
                }
            },

            inputView: Ember.View.extend({
                template: function(){
                    var dataSource = this.get('parentView.meta.dataSource');
                    return Ember.Handlebars.compile('{{input type="text" value=object.' + dataSource + ' placeholder=meta.placeholder validator="collection" name=meta.attributes.name}}');
                }.property()
            })
        });
    };
});