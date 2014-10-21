define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormCollectionView = Ember.View.extend(UMI.FormViewMixin, UMI.FormCollectionElementsMixin, {
                /**
                 * Шаблон формы
                 * @property layout
                 * @type String
                 */
                layoutName: 'partials/formCollection',

                classNames: ['s-margin-clear', 's-full-height', 'umi-validator', 'umi-form-control'],

                willDestroyElement: function() {
                    this.get('controller').removeObserver('object.validErrors.@each');
                }
            });
        };
    }
);