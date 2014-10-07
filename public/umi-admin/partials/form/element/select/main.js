define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormSelectElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            classNames: ['small-12', 'large-4'],

            template: Ember.Handlebars.compile('{{view "select" object=view.object meta=view.meta name=view.meta.attributes.name}}')
        });

        UMI.SelectView = Ember.Select.extend(UMI.ElementValidateMixin, {
            attributeBindings: ['meta.dataSource:name'],

            optionLabelPath: function() {
                return 'content.label';
            }.property(),

            optionValuePath: function() {
                return 'content.value';
            }.property(),

            prompt: function() {
                var meta = this.get('meta.choices');
                var choicesHasPrompt;

                if (meta && Ember.typeOf(meta) === 'array') {
                    choicesHasPrompt = meta.findBy('value', '');
                }

                if (choicesHasPrompt) {
                    return choicesHasPrompt.label;
                } else {
                    var label = 'Nothing is selected';
                    var translateLabel = UMI.i18n.getTranslate(label, 'form');
                    return translateLabel ? translateLabel : label;
                }
            }.property('meta.placeholder'),

            content: null,

            init: function() {
                this._super();
                var object = this.get('object');
                var choices = Ember.get(object, 'choices') || [];
                this.set('selection', choices.findBy('value', Ember.get(object, 'value')));
                this.set('content', choices);
            },

            didInsertElement: function() {
                var prompt = this.$().find('option')[0];
                var validators = this.get('meta.validators') || [];
                validators = validators.findBy('type', 'required');

                if (!prompt.value && validators) {
                    prompt.disabled = true;
                }
            }
        });

        UMI.FormSelectCollectionElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            classNames: ['small-12', 'large-4'],

            template: Ember.Handlebars.compile('{{view "selectCollection" object=view.object meta=view.meta}}')
        });

        UMI.SelectCollectionView = Ember.Select.extend(UMI.ElementValidateMixin, {
            attributeBindings: ['meta.dataSource:name'],

            isLazy: false,

            optionLabelPath: function() {
                return this.get('isLazy') ? 'content.displayName' : 'content.label';
            }.property(),

            optionValuePath: function() {
                return this.get('isLazy') ? 'content.id' : 'content.value';
            }.property(),

            prompt: function() {
                var meta = this.get('meta.choices');
                var choicesHasPrompt;

                if (meta && Ember.typeOf(meta) === 'array') {
                    choicesHasPrompt = meta.findBy('value', '');
                }

                if (choicesHasPrompt) {
                    return choicesHasPrompt.label;
                } else {
                    var label = 'Nothing is selected';
                    var translateLabel = UMI.i18n.getTranslate(label, 'form');
                    return translateLabel ? translateLabel : label;
                }
            }.property('meta.placeholder'),

            content: null,

            changeValue: function() {
                var object = this.get('object');
                var property = this.get('meta.dataSource');
                var selectedObject = this.get('selection');
                var value;

                if (this.get('isLazy')) {
                    value = selectedObject ? selectedObject : undefined;
                    object.set(property, value);
                    object.changeRelationshipsValue(property, selectedObject ? selectedObject.get('id') : undefined);
                } else {
                    value = selectedObject ? selectedObject.value : object.getDefaultValueForProperty(property);
                    object.set(property, value);
                }
            }.observes('value'),

            init: function() {
                this._super();
                var self = this;
                var object = this.get('object');
                var property = this.get('meta.dataSource');
                this.set('isLazy', this.get('meta.lazy'));

                if (this.get('isLazy')) {
                    var store = self.get('controller.store');
                    var relatedCollectionName;

                    object.eachRelationship(function(name, meta) {
                        if (name === property) {
                            relatedCollectionName = Ember.get(meta, 'type.typeKey');
                        }
                    });

                    Ember.warn('Name of related collection is undefined.', relatedCollectionName);

                    var relationDidFetch = function(relatedObject) {
                        Ember.set(object.get('loadedRelationshipsByName'), property, relatedObject ?
                            relatedObject.get('id') : undefined);
                        self.set('selection', relatedObject);
                    };

                    var relatedCollection = store.all(relatedCollectionName);

                    self.set('content', relatedCollection);
                    var relatedObject = object.get(property);

                    if (Ember.typeOf(relatedObject) === 'instance') {
                        return relatedObject.then(function(relatedObject) {
                            relationDidFetch(relatedObject);
                        });
                    } else {
                        relationDidFetch();
                    }
                } else {
                    self.set('selection', this.get('meta.choices').findBy('value', object.get(property)));
                    self.set('content', this.get('meta.choices'));
                    Ember.run.next(self, function() {
                        var self = this;
                        self.addObserver('object.' + property, function() {
                            Ember.run.once(function() {
                                self.set('selection', self.get('meta.choices').findBy('value', object.get(property)));
                            });
                        });
                    });
                }
            },

            didInsertElement: function() {
                var property = this.get('meta.dataSource');
                var collectionName = this.get('object').constructor.typeKey;
                var metadata = this.get('controller.store').metadataFor(collectionName);
                var validators = Ember.get(metadata, 'validators.' + property);

                if (validators && Ember.typeOf(validators) === 'array') {
                    validators = validators.findBy('type', 'required');

                    if (validators) {
                        var prompt = this.$().find('option')[0];

                        if (!prompt.value && validators) {
                            prompt.disabled = true;
                        }
                    }
                }
            },

            willDestroyElement: function() {
                this.removeObserver('value');
                this.removeObserver('object.' + this.get('meta.dataSource'));
            }
        });
    };
});