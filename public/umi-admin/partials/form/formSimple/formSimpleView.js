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
                        var self = this;
                        if (handler) {
                            handler.addClass('loading');
                        }
                        var data = this.$().serialize();

                        $.ajax({
                            type: 'POST',
                            url: self.get('action'),
                            global: false,
                            data: data,

                            success: function(results) {
                                var meta = Ember.get(results, 'result.save');
                                var context = self.get('context');
                                if (meta) {
                                    Ember.set(context, 'control.meta', meta);
                                }
                                handler.removeClass('loading');
                                var params = {type: 'success', content: UMI.i18n.getTranslate('Saved') + '.'};
                                UMI.notification.create(params);
                            },

                            error: function(results) {
                                var result = Ember.get(results, 'responseJSON.result');
                                var meta = Ember.get(result, 'save');
                                var context = self.get('context');
                                if (meta && Ember.get(meta, 'type')) {
                                    Ember.set(context, 'control.meta', meta);
                                }
                                var error = Ember.get(result, 'error');
                                if (error && Ember.get(error, 'message')) {
                                    var params = {type: 'error', content: Ember.get(error, 'message')};
                                    UMI.notification.create(params);
                                }
                                handler.removeClass('loading');
                            }
                        });
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