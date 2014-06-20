define(
    [
        'App',
        'text!./templates/sideMenu.hbs'
    ],
    function(UMI, sideMenuTpl){
        'use strict';

        UMI.SideMenuController = Ember.ObjectController.extend({
            needs: ['component'],
            settings: function(){
                return this.get('controllers.component.sideBarControl');
            }.property('model')
        });

        UMI.SideMenuView = Ember.View.extend({
            layout: Ember.Handlebars.compile(sideMenuTpl),
            linksBinding: 'controller.settings.params.links'
        });
    }
);