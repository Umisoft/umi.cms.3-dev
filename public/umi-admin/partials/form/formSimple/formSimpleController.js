define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormSimpleController = Ember.ObjectController.extend(UMI.FormControllerMixin, {
                formElementsBinding: 'control.meta.elements',

                actions: {
                    submit: function(params) {
                        var form = Ember.get(params, 'form');
                        var handler = Ember.get(params, 'handler');
                        var data = form.$().serialize();

                        if (handler) {
                            handler.addClass('loading');
                        }

                        $.ajax({
                            type: 'POST',
                            url: form.get('action'),
                            global: false,
                            data: data,

                            success: function(results) {
                                var meta = Ember.get(results, 'result.save');
                                var context = form.get('context');
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
                                var context = form.get('context');
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
                }
            });
        };
    }
);