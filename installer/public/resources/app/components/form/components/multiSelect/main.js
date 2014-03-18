define(['App'], function(UMI){
    'use strict';

    UMI.MultiSelectComponent = Ember.Component.extend({
        classNames: ['row', 'collapse'],
        layout: Ember.Handlebars.compile('<div class="small-9 columns"><div class="umi-multi-select">{{yield}}</div></div><div class="small-3 columns"><span class="postfix radius">Label</span></div>')
    });
});