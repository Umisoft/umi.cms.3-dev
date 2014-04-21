define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.DatePickerComponent = Ember.Component.extend({
            tagName: 'div',
            classNames: ['umi-input-wrapper-date'],
            object: null,
            property: null,
            valueObject: function(){
                var dateProperty = this.get('object.' + this.get("property"));
                if(dateProperty){
                    dateProperty = JSON.parse(dateProperty).date;
                }
                return dateProperty;
            }.property('object', 'property'),
            changeValueObject: function(){
                var dateProperty = this.get('object.' + this.get("property")) || null;
                if(dateProperty){
                    dateProperty = JSON.parse(dateProperty);
                    dateProperty.date = this.get('valueObject');
                    dateProperty = JSON.stringify(dateProperty);
                }
                this.get('object').set(this.get('property'), dateProperty);
            }.observes('valueObject'),
            layout: Ember.Handlebars.compile('{{input type="text" class="umi-date" value=valueObject}}'),
            didInsertElement: function(){
                var el = this.$().children('.umi-date');
                el.jdPicker({date_format: "dd/mm/YYYY"});
            }
        });
    };
});