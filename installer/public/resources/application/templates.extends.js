define([], function(){
        'use strict';

        return function(){
            Ember.TEMPLATES['UMI/module/errors'] = Ember.TEMPLATES['UMI/component/errors'] = Ember.TEMPLATES['UMI/errors'];
        };
    }
);