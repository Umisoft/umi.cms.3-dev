define([
    'App', 'toolbar'
], function(UMI){
    'use strict';

    UMI.TopBarView = Ember.View.extend({
        templateName: 'partials/topBar',
        activeProject: function(){
            return window.location.host;
        }.property(),
        siteUrl: function(){
            return Ember.get(window, 'UmiSettings.baseSiteURL');
        }.property(),
        dropdownView: UMI.DropdownButtonView.extend({
            template: function(){
                var userName = Ember.get(window, 'UmiSettings.user.displayName');
                return Ember.Handlebars.compile(userName + '<ul class="f-dropdown right"><li><a href="javascript:void(0)" {{action "logout"}}><i class="icon icon-exit"></i> {{i18n "Logout"}}</a></li></ul>');
            }.property()
        })
    });
});