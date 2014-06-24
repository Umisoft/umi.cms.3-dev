define(['App', 'text!./permissions.hbs', 'text!./partial.hbs'], function(UMI, permissionsTemplate, partialTemplate){
    "use strict";

    return function(){
        UMI.PermissionsView = Ember.View.extend({
            template: Ember.Handlebars.compile(permissionsTemplate),
            objectProperty: function(){
                var object = this.get('object');
                var dataSource = this.get('meta.dataSource');
                var property = object.get(dataSource) || "{}";
                try{
                    property = JSON.parse(property);
                    if(Ember.typeOf(property) !== 'object'){
                        property = {};
                    }
                } catch(error){
                    this.get('controller').send('backgroundError', error);
                } finally{
                    return property;
                }
            }.property('object'),

            setObjectProperty: function(checkbox, path, isChecked){
                var object = this.get('object');
                var objectProperty = this.get('objectProperty');
                var componentRoles = objectProperty[path];
                var childrenCheckboxes;
                var i;
                var childComponentName;
                var currentRole = checkbox.value;

                if(Ember.typeOf(componentRoles) !== 'array'){
                    componentRoles = objectProperty[path] = [];
                }

                function checkedParentCheckboxes(checkbox){
                    if(checkbox){
                        var parentCheckbox = $(checkbox).closest('.umi-permissions-component').closest('.umi-permissions-role-list-item').children('.umi-permissions-role').find('.umi-permissions-role-checkbox');
                        if(parentCheckbox.length){
                            parentCheckbox[0].checked = true;
                            //intermediate
                            var parentComponentName = $(parentCheckbox[0]).closest('.umi-permissions-role-label').attr('data-permissions-component-path');
                            var parentComponentRoles = objectProperty[parentComponentName];
                            if(Ember.typeOf(parentComponentRoles) !== 'array'){
                                parentComponentRoles = objectProperty[parentComponentName] = [];
                            }
                            if(!parentComponentRoles.contains(parentCheckbox[0].name)){
                                parentComponentRoles.push(parentCheckbox[0].name);
                            }
                            checkedParentCheckboxes(parentCheckbox[0]);
                        }
                    }
                }

                if(isChecked){
                    if(!componentRoles.contains(currentRole)){
                        componentRoles.push(currentRole);
                    }
                    checkedParentCheckboxes(checkbox);
                } else{
                    if(componentRoles.contains(currentRole)){
                        componentRoles = componentRoles.without(currentRole);
                    }
                    childrenCheckboxes = $(checkbox).closest('.umi-permissions-role-list-item').children('.umi-permissions-component').find('.umi-permissions-role-checkbox');
                    if(childrenCheckboxes.length){
                        for(i = 0; i < childrenCheckboxes.length; i++){
                            childrenCheckboxes[i].checked = false;
                            childComponentName = $(childrenCheckboxes[i]).closest('.umi-permissions-role-label').attr('data-permissions-component-path');
                            var childComponentRoles = objectProperty[childComponentName];
                            if(Ember.typeOf(childComponentRoles) !== 'array'){
                                childComponentRoles = objectProperty[childComponentName] = [];
                            }
                            childComponentRoles = childComponentRoles.without(childrenCheckboxes[i].name);
                        }
                    }
                }
                object.set(this.get('meta.dataSource'), JSON.stringify(objectProperty));
            },

            didInsertElement: function(){
                var self = this;
                var $el = this.$();
                var property = this.get('objectProperty');

                var checkedInput = function(objectProperty, componentName){
                    var i;
                    var checkbox;
                    if(Ember.typeOf(objectProperty[componentName]) === 'array'){
                        for(i = 0; i < objectProperty[componentName].length; i++){
                            checkbox = $el.find('[data-permissions-component-path="' + componentName + '"]').find('.umi-permissions-role-checkbox').filter('[name="' + objectProperty[componentName][i] + '"]');
                            if(checkbox.length){
                                checkbox[0].checked = true;
                            }
                        }
                    }
                };

                for(var key in property){
                    if(property.hasOwnProperty(key)){
                        checkedInput(property, key);
                    }
                }

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

                $el.find('.umi-permissions-role-checkbox').on('change', function(){
                    var isChecked = this.checked;
                    var componentName = $(this).closest('.umi-permissions-role-label').attr('data-permissions-component-path');
                    self.setObjectProperty(this, componentName, isChecked);
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