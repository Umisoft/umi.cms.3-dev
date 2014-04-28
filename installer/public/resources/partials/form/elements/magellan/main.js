define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.MagellanView = Ember.View.extend({
            classNames: ['magellan-menu', 's-full-height-before', 's-unselectable'],
            focusName: null,
            elementView: Ember.View.extend({
                isFieldset: function(){
                    return this.get('content.type') === 'fieldset';
                }.property()
            }),
            buttonView: Ember.View.extend({
                tagName: 'a',
                classNameBindings: ['isFocus:focus'],
                isFocus: function(){
                    return this.get('model.name') === this.get('parentView.parentView.focusName');
                }.property('parentView.parentView.focusName'),
                click: function(){
                    var fieldset = document.getElementById('fieldset-' + this.get('model.name'));
                    $(fieldset).closest('.magellan-content').animate({'scrollTop': fieldset.parentNode.offsetTop - parseFloat(getComputedStyle(fieldset).marginTop)}, 0);
                }
            }),
            init: function(){
                var elements = this.get('elements');
                elements = elements.filter(function(item){
                    return item.type === 'fieldset';
                });
                this.set('focusName', elements.get('firstObject.name'));
            },
            didInsertElement: function(){
                var self = this;
                var scrollArea = $('.magellan-menu').parent().find('.magellan-content');//TODO: По хорошему нужно выбирать элемент через this.$()
                if(!scrollArea.length){
                    return;
                }
                scrollArea.on('scroll.umi.magellan', function(){
                    var scrollOffset = $(this).scrollTop();
                    var focusField;
                    var fieldset = $(this).find('fieldset');
                    var scrollElement;
                    for(var i = 0; i < fieldset.length; i++){
                        scrollElement = fieldset[i].parentNode.offsetTop;
                        if(scrollElement - parseFloat(getComputedStyle(fieldset[i]).marginTop) <= scrollOffset && scrollOffset <= scrollElement + fieldset[i].offsetHeight){
                            focusField = fieldset[i];
                        }
                    }
                    if(focusField){
                        self.set('focusName', focusField.id.replace(/^fieldset-/g, ''));
                    }
                });
            }
        });
    };
});