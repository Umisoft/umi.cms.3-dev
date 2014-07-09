define([
    'App', 'toolbar'
], function(UMI){
    'use strict';

    UMI.TopBarView = Ember.View.extend({
        templateName: 'partials/topBar',
        dropdownView: UMI.DropdownButtonView.extend({
            template: function(){
                return Ember.Handlebars.compile('mail@yandex.ru<ul class="f-dropdown right"><li><a href="javascript:void(0)" {{action "logout"}}>{{i18n "Logout"}}</a></li></ul>');
            }.property()
        })
    });
});