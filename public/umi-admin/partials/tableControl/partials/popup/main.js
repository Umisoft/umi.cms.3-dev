define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.TableControlPopupController = Ember.ObjectController.extend({
            selectedFields: Ember.computed.filterBy('fieldsList', 'value', 1),

            fieldsList: function() {
                var fieldsList = [];
                var allFields = this.get('allFields');
                var visibleFields = this.get('visibleFields');

                for (var i = 0; i < allFields.length; i++) {
                    fieldsList.push(allFields[i]);
                    fieldsList[fieldsList.length - 1].value =
                        visibleFields.findBy('dataSource', allFields[i].dataSource) ? 1 : 0;
                }

                return fieldsList;
            }.property('allFields', 'visibleFields'),

            isDirty: function() {
                var selectedFields = this.get('selectedFields');
                var visibleFields = this.get('visibleFields');
                var isDirty = 0;

                if (!selectedFields.length) {
                    return isDirty;
                }

                if (selectedFields.length !== visibleFields.length) {
                    ++isDirty;
                } else {
                    for (var i = 0; i < selectedFields.length; i++) {
                        if (!visibleFields.findBy('dataSource', selectedFields[i].dataSource)) {
                            ++isDirty;
                            break;
                        }
                    }
                }

                return isDirty;
            }.property('selectedFields.@each'),

            actions: {
                setDefault: function() {
                    var fieldsList = [];
                    var allFields = this.get('allFields');
                    var defaultFields = this.get('defaultFields');

                    for (var i = 0; i < allFields.length; i++) {
                        fieldsList.push(allFields[i]);
                        fieldsList[fieldsList.length - 1].value = defaultFields.contains(allFields[i].dataSource) ?
                            1 : 0;
                    }

                    this.set('fieldsList', fieldsList);
                },

                apply: function() {
                    if (!this.get('isDirty')) {
                        return;
                    }

                    var selectedFields = this.get('selectedFields');
                    var allFields = this.get('allFields');
                    var fields = [];
                    var field;
                    for (var i = 0; i < selectedFields.length; i++) {
                        field = allFields.findBy('dataSource', selectedFields[i].dataSource);
                        if (field) {
                            fields.push(field);
                        }
                    }

                    if (fields.length) {
                        this.get('tableController').set('visibleFields', fields);
                        this.send('closePopup');
                    }
                }
            }
        });

        UMI.TableControlPopupView = Ember.View.extend({
            templateName: 'partials/tableControl/configuration',

            classNames: ['s-full-height'],

            didInsertElement: function() {
                var $el = this.$();
                var $scrollContent = $el.find('.s-scroll-wrap');
                var scroll = new IScroll($scrollContent[0], UMI.config.iScroll);
                this.set('iScroll', scroll);
            }
        });
    };
});
