define([
    'App'
], function(UMI) {
    'use strict';

    UMI.UpdateLayoutView = Ember.View.extend(UMI.i18nInterface, {
        data: null,

        dictionaryNamespace: 'updateLayout',

        localDictionary: function() {
            return this.get('data.control.i18n');
        }.property(),

        classNames: ['row', 's-full-height'],

        templateName: 'partials/updateLayout',

        buttonLabel: function() {
            var elements = this.get('data.control.submitToolbar');
            if (Ember.typeOf(elements) === 'array') {
                return Ember.get(elements[0], 'attributes.label');
            }
        }.property('data.control.submitToolbar'),

        actions: {
            update: function() {
                var self = this;
                var button = self.$().find('.button');
                var updateSource;

                try {
                    var componentController = self.get('container').lookup('controller:component');
                    if (componentController) {
                        updateSource = componentController.get('settings.actions.update.source');

                        $.ajax({
                            type: "POST",
                            url: updateSource,
                            global: false,
                            beforeSend: function() {
                                button.addClass('loading');
                            },
                            success: function(results) {
                                button.removeClass('loading');
                                updateSource = Ember.get(results, 'result.update');
                                if (updateSource) {
                                    window.location.href = updateSource;
                                } else {
                                    throw new Error('Update url not found.');
                                }
                            },
                            error: function(error) {
                                button.removeClass('loading');
                                self.get('controller').send('backgroundError', error);
                            }
                        });
                    } else {
                        throw new Error('Component controller not found.');
                    }
                } catch (error) {
                    self.get('controller').send('backgroundError', error);
                }
            }
        }
    });
});