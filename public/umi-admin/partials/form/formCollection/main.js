define(
    ['App', './formCollectionController', './formCollectionElementValidateMixin', './formCollectionElementsMixin',
        './formCollectionElementMixin', './formCollectionView'],

    function(UMI, formCollectionController, formCollectionElementValidateMixin, formCollectionElementsMixin, formCollectionElementMixin,
        formCollectionView) {
        'use strict';

        return function() {
            formCollectionController();
            formCollectionElementValidateMixin();
            formCollectionElementsMixin();
            formCollectionElementMixin();
            formCollectionView();
        };
    }
);
