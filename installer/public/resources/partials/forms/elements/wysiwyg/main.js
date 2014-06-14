define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.HtmlEditorView = Ember.View.extend({
            classNames: ['ckeditor-row'],

            template: function(){
                var textarea = '{{textarea value=view.meta.attributes.value placeholder=view.meta.placeholder name=view.meta.attributes.name}}';
                return Ember.Handlebars.compile(textarea);
            }.property(),

            didInsertElement: function(){
                var self = this;
                var el = this.$().children('textarea');
                var edit = CKEDITOR.replace(el[0].id);
            }
        });

        UMI.HtmlEditorCollectionView = Ember.View.extend(UMI.InputValidate, {
            classNames: ['ckeditor-row'],

            textareaId: function(){
                return 'textarea-' + this.get('id');
            }.property(),

            template: function(){
                var textarea = '<textarea id="{{unbound view.textareaId}}" placeholder="{{unbound view.meta.placeholder}}" name="{{unbound view.meta.attributes.name}}">{{unbound view.object.' + this.get('meta.dataSource') + '}}</textarea>';
                var validate = this.validateErrorsTemplate();
                return Ember.Handlebars.compile(textarea + validate);
            }.property(),

            setTextareaValue: function(edit){
                if(this.$() && this.$().children('textarea').length){
                    var value = this.get('object.' + this.get('meta.dataSource'));
                    if(edit && edit.getData() !== value){
                        edit.setData(value);
                    }
                }
            },

            updateContent: function(event, edit){
                var self = this;
                if(event.editor.checkDirty()){
                    self.get('object').set(self.get('meta.dataSource'), edit.getData());
                }
            },

            didInsertElement: function(){
                var self = this;
                var el = this.$().children('textarea');
                var edit = CKEDITOR.replace(el[0].id);

                edit.on('blur', function(event){
                    Ember.run.once(self, 'updateContent', event, edit);
                });

                edit.on('key', function(event){// TODO: Это событие было добавлено только из-за того, что событие save срабатывает быстрее blur. Кажется можно сделать лучше.
                    Ember.run.once(self, 'updateContent', event, edit);
                });

                self.addObserver('object.' + self.get('meta.dataSource'), function(){
                    Ember.run.once(self, 'setTextareaValue', edit);
                });
            },

            willDestroyElement: function(){
                var self = this;
                self.removeObserver('object.' + self.get('meta.dataSource'));
            }
        });
    };
});