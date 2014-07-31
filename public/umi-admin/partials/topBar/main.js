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
        }),
        moduleDropdownView: UMI.DropdownButtonView.extend({
            template: function(){
                return Ember.Handlebars.compile('<i class="icon icon-butterfly"></i><ul class="f-dropdown dropdown-double-columns">{{#each view.modules}}<li>{{#link-to "module" name class="umi-top-bar-dropdown-modules-item"}}<i class="umi-top-bar-module-icon umi-dock-module-{{unbound name}}"></i> <span>{{displayName}}</span>{{/link-to}}</li>{{/each}}</ul>');
            }.property(),
            modules: function(){
                return this.get('controller.modules');
            }.property(),
            click: function(event){
                var $button = this.$();
                if($(event.target).closest('.umi-top-bar-dropdown-modules-item').length){
                    $button.removeClass('open');
                }
            }
        })
    });
});