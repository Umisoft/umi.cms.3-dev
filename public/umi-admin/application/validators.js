define([], function(){
    'use strict';


    return function(UMI){

        var Validator = Ember.Object.extend({
            /**
             * Валидатор элементов формы
             * @method validateForm
             */
            validateForm: function(){},

            filterProperty: function(value, filters){
                var self = this;
                var i;

                for(i = 0; i < filters.length; i++){
                    value = self.itemFilterProperty(value, Ember.get(filters[i], 'type'));
                }

                return value;
            },

            itemFilterProperty: function(value, filterName){
                if(Ember.typeOf(this[filterName]) === 'function'){
                    return this[filterName](value);
                } else{
                    Ember.warn('Filter "' + filterName + '" was not defined.');
                }
            },

            /**
             *
             * @param value
             * @param validators
             * @returns {Array}
             */
            validateProperty: function(value, validators){
                var i;
                var errorList = [];

                for(i = 0; i < validators.length; i++){
                    switch(validators[i].type){
                        case "required":
                            if(!value){
                                errorList.push(validators[i].message);
                            }
                            break;
                        case "regexp":
                            var pattern = eval(validators[i].options.pattern); //TODO: Заменить eval
                            if(!pattern.test(value)){
                                errorList.push(validators[i].message);
                            }
                            break;
                    }
                }

                if(errorList.length){
                    return errorList;
                }
            },

            stringTrim: function(value){
                if(value){
                    value = value.replace(/^\s+|\s+$/g, '');
                }
                return value;
            },

            htmlSafe: function(value){
                return Ember.String.htmlSafe(value);
            },

            stripTags: function(value){//TODO: add filter
                return value;
            },

            slug: function(value){//TODO: add filter
                return value;
            }
        });

        UMI.validator = Validator.create({});
    };
});