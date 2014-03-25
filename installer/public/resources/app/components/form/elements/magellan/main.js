define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.MagellanView = Ember.View.extend({
            classNames: ['magellan-menu', 's-full-height-before'],
            focusName: null,
            buttonView: Ember.View.extend({
                tagName: 'a',
                classNameBindings: ['isFocus:focus'],
                isFocus: function(){
                    return this.get('model.name') === this.get('parentView.focusName');
                }.property('parentView.focusName'),
                click: function(){
                    var fieldset = document.getElementById('fieldset-' + this.get('model.name'));
                    $(fieldset).closest('.maggelan-content').animate({'scrollTop': fieldset.offsetTop - parseFloat(getComputedStyle(fieldset).marginTop)}, 0);
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
                var scrollArea = $('.magellan-menu').parent().find('.maggelan-content');//TODO: По хорошему нужно выбирать элемент через this.$()
                if(!scrollArea.length){
                    return;
                }
                scrollArea.on('scroll.umi.magellan', function(){
                    var scrollOffset = $(this).scrollTop();
                    var focusField;
                    var fieldset = $(this).children('fieldset');
                    var scrollElement;
                    for(var i = 0; i < fieldset.length; i++){
                        scrollElement = fieldset[i].offsetTop;
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