define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.HtmlEditorComponent = Ember.Component.extend({
            tagName: 'div',
            object: null,
            property: null,
            valueObject: function(){
                return this.get('object.' + this.get("property"));
            }.property('object', 'property'),
            classNames: ['ckeditor-row'],
            layout: Ember.Handlebars.compile('{{textarea value=valueObject placeholder=meta.placeholder}}'),

            didInsertElement: function(){
                var self = this;
                var el = this.$().children('textarea');
                var edit = CKEDITOR.replace(el[0].id);
                var updateContent = function(event){
                    if(event.editor.checkDirty()){
                        self.get('object').set(self.get('property'), edit.getData());
                    }
                };

                edit.on('blur', function(event){
                    updateContent(event);
                });

                edit.on('key', function(event){// TODO: Это событие было добавлено только из-за того, что событие save срабатывает быстрее blur. Кажется можно сделать лучше.
                    updateContent(event);
                });
            }
        });
    };
});