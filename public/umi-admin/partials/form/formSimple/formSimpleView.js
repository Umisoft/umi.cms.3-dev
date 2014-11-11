define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormSimpleView = Ember.View.extend(UMI.FormViewMixin, UMI.FormSimpleElementsMixin, {
                /**
                 * Шаблон формы
                 * @property layout
                 * @type String
                 */
                layoutName: 'partials/formSimple',

                /**
                 * Классы view
                 * @property classNames
                 * @type Array
                 */
                classNames: ['s-margin-clear', 's-full-height', 'umi-form-control', 'umi-validator'],

                attributeBindings: ['action'],

                action: function() {
                    return this.get('context.control.meta.attributes.action');
                }.property('context.control.meta.attributes.action'),

                actions: {
                    submit: function(handler) {
                        var params = {
                            handler: handler,
                            form: this
                        };
                        this.get('controller').send('submit', params);
                    }
                },

                submitToolbarView: UMI.SubmitToolbarView.extend({
                    elementView: Ember.View.extend(UMI.ToolbarElement, {
                        buttonView: function() {
                            var params = {};
                            if (this.get('context.behaviour.name') === 'save') {
                                params = {
                                    actions: {
                                        save: function() {
                                            this.get('parentView.parentView.parentView').send('submit', this.$());
                                        }
                                    }
                                };
                            }
                            return UMI.ButtonView.extend(params);
                        }.property()
                    })
                })
            });
        };
    }
);