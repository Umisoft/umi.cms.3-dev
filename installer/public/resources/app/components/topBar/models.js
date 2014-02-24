define(['App'], function (UMI) {
    'use strict';

    return function () {
        UMI.SiteList = DS.Model.extend({
            slug: DS.attr('string'),
            title: DS.attr('string'),
            url: DS.attr('string'),
            module_list: DS.hasMany('module_list', {async: true})
        });
    };
});