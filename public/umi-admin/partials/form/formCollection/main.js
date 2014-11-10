define(
    ['App', './formCollectionController', './formCollectionElementsMixin', './formCollectionView'],

    function(UMI, formCollectionController, formCollectionElementsMixin, formCollectionView) {
        'use strict';

        return function() {
            formCollectionController();
            formCollectionElementsMixin();
            formCollectionView();
        };
    }
);
