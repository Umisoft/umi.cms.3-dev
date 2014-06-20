define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.InputValidate = Ember.Mixin.create({
            validator: null,
            validateElement: null,

            focusOut: function(){
                if(this.get('validator') === 'collection'){
                    var object = this.get('object');
                    object.filterProperty(this.get('meta.dataSource'));
                    object.validateProperty(this.get('meta.dataSource'));
                }
            },

            focusIn: function(){
                if(this.get('validator') === 'collection'){
                    var object = this.get('object');
                    object.clearValidateForProperty(this.get('meta.dataSource'));
                }
            },

            validateErrorsTemplate: function(){
                var propertyName = this.get('meta.dataSource');
                var template = '{{#if view.object.validErrors.' + propertyName + '}}' +
                    '<small class="error">{{#each object.validErrors.' + propertyName + '}}' +
                    '{{message}} ' +
                    '{{/each}}</small>' +
                    '{{/if}}';
                return template;
            }
        });
    };
});