define(['App', 'text!./fileElement.hbs'], function(UMI, fileElement){
    "use strict";

    Ember.TEMPLATES['UMI/components/file-element'] = Ember.Handlebars.compile(fileElement);

    return function(){
        UMI.FileElementComponent = Ember.Component.extend(UMI.InputValidate, {
            classNames: ['umi-element', 'umi-element-file'],

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
                }.property(),

                willDestroyElement: function(){
                    //Может ли возникнуть необходимость подтверждения действий для попапа перед переходом на другой роут?
                    //При возврате на роут возможно есть смысл показывать попапы закрытые при уходе?
                    //По-хорошему, попапу нужно добавлять id и уже по нему удалять
                    $('.umi-popup').remove();
                }
            })
        });
    };
});