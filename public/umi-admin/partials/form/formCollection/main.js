define(
    ['App', './formCollectionController', './formCollectionElementsMixin', './formCollectionElementMixin',
        './formCollectionView'],

    function(UMI, formCollectionController, formCollectionElementsMixin, formCollectionElementMixin,
        formCollectionView) {
        'use strict';

        return function() {
            formCollectionController();
            formCollectionElementsMixin();
            formCollectionElementMixin();
            formCollectionView();
        };
    }
);
