define(['App', 'text!./permissions.hbs', 'text!./partial.hbs'], function(UMI, permissionsTemplate, partialTemplate){
    "use strict";

    return function(){
        UMI.PermissionsView = Ember.View.extend({
            template: Ember.Handlebars.compile(permissionsTemplate),
            didInsertElement: function(){
                var $el = this.$();
                var accordion = $el.find('.accordion');
                accordion.each(function(index){
                    var triggerButton = $(accordion[index]).find('.accordion-navigation-button');
                    var triggerBlock = $(accordion[index]).find('.content');
                    triggerButton.on('click', function(){
                        triggerBlock.toggleClass('active');
                        triggerButton.find('.icon').toggleClass('icon-right icon-bottom');
                    });
                });
                $el.on('click.umi.permissions', '.umi-permissions-role-button-expand', function(){
                    $(this).closest('li').children('.umi-permissions-component').toggleClass('expand');
                    $(this).find('.icon').toggleClass('icon-right icon-bottom');
                });
            }
        });

        UMI.PermissionsPartialView = Ember.View.extend({
            tagName: 'ul',
            classNames: ['no-bullet', 'umi-permissions-role-list'],
            template: Ember.Handlebars.compile(partialTemplate)
        });
    };
});