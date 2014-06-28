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
                        var checkedSiblingsCheckboxes = 0;
                        var checkboxes = $(checkbox).closest('.umi-permissions-role-list-item').children('.umi-permissions-component').find('.umi-permissions-role-checkbox');
                        for(var i = 0; i < checkboxes.length; i++){
                            if(checkboxes[i].checked){
                                checkedSiblingsCheckboxes++;
                            }
                        }
                        if(checkedSiblingsCheckboxes === checkboxes.length){
                            checkbox.indeterminate = false;
                        } else{
                            checkbox.indeterminate = true;
                        }

                        var parentCheckbox = $(checkbox).closest('.umi-permissions-component').closest('.umi-permissions-role-list-item').children('.umi-permissions-role').find('.umi-permissions-role-checkbox');
                        if(parentCheckbox.length){
                            parentCheckbox[0].checked = true;

                            var parentComponentName = $(parentCheckbox[0]).closest('.umi-permissions-role-label').attr('data-permissions-component-path');
                            var parentComponentRoles = objectProperty[parentComponentName];
                            if(Ember.typeOf(parentComponentRoles) !== 'array'){
                                parentComponentRoles = objectProperty[parentComponentName] = [];
                            }
                            if(!parentComponentRoles.contains(parentCheckbox[0].name)){
                                parentComponentRoles.push(parentCheckbox[0].name);
                                parentComponentRoles.sort();
                            }
                            checkedParentCheckboxes(parentCheckbox[0]);
                        }
                    }
                }

                function setParentCheckboxesIndeterminate(checkbox){
                    var parentCheckbox =$(checkbox).closest('.umi-permissions-component').closest('.umi-permissions-role-list-item').children('.umi-permissions-role').find('.umi-permissions-role-checkbox');
                    if(parentCheckbox.length){
                        if(parentCheckbox[0].checked){
                            parentCheckbox[0].indeterminate = true;
                        }
                        setParentCheckboxesIndeterminate(parentCheckbox[0]);
                    }
                }

                if(isChecked){
                    if(!componentRoles.contains(currentRole)){
                        componentRoles.push(currentRole);
                        componentRoles.sort();
                    }
                    checkedParentCheckboxes(checkbox);
                } else{
                    if(componentRoles.contains(currentRole)){
                        objectProperty[path] = componentRoles.without(currentRole);
                        if(!objectProperty[path].length){
                            delete objectProperty[path];
                        }
                    }

                    checkbox.indeterminate = false;
                    setParentCheckboxesIndeterminate(checkbox);

                    childrenCheckboxes = $(checkbox).closest('.umi-permissions-role-list-item').children('.umi-permissions-component').find('.umi-permissions-role-checkbox');
                    if(childrenCheckboxes.length){
                        for(i = 0; i < childrenCheckboxes.length; i++){
                            childrenCheckboxes[i].checked = false;
                            childrenCheckboxes[i].indeterminate = false;
                            childComponentName = $(childrenCheckboxes[i]).closest('.umi-permissions-role-label').attr('data-permissions-component-path');
                            var childComponentRoles = objectProperty[childComponentName];
                            if(Ember.typeOf(childComponentRoles) !== 'array'){
                                childComponentRoles = objectProperty[childComponentName] = [];
                            }
                            childComponentRoles = objectProperty[childComponentName] = childComponentRoles.without(childrenCheckboxes[i].name);
                            if(!childComponentRoles.length){
                                delete objectProperty[childComponentName];
                            }
                        }
                    }
                }
                if(JSON.stringify(objectProperty) === '{}'){
                    objectProperty = [];
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

                function setCheckboxIndeterminate(checkbox){
                    if(checkbox.checked){
                        var childrenCheckboxes =$(checkbox).closest('.umi-permissions-role-list-item').children('.umi-permissions-component').find('.umi-permissions-role-checkbox');
                        var checkedChildrenCheckboxes = 0;
                        for(var i = 0; i < childrenCheckboxes.length; i++){
                            if(childrenCheckboxes[i].checked){
                                checkedChildrenCheckboxes++;
                            }
                        }
                        if(checkedChildrenCheckboxes === childrenCheckboxes.length){
                            checkbox.indeterminate = false;
                        } else{
                            checkbox.indeterminate = true;
                        }
                    }
                }

                for(var key in property){
                    if(property.hasOwnProperty(key)){
                        checkedInput(property, key);
                    }
                }

                var $checkboxes = $el.find('.umi-permissions-role-checkbox');
                for(var i = 0; i < $checkboxes.length; i++){
                    setCheckboxIndeterminate($checkboxes[i]);
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
                    var component = $(this).closest('li').children('.umi-permissions-component');
                    component.toggleClass('expand');
                    $(this).find('.icon').toggleClass('icon-right icon-bottom');
                    component.find('.umi-permissions-component').removeClass('expand');
                    component.find('.umi-permissions-role-button-expand').find('.icon').addClass('icon-right').removeClass('icon-bottom');
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