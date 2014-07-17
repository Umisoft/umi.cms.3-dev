define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.MagellanView = Ember.View.extend({
            classNames: ['magellan-menu', 's-full-height-before', 's-unselectable'],
            focusId: null,
            elementView: Ember.View.extend({
                isFieldset: function(){
                    return this.get('content.type') === 'fieldset';
                }.property()
            }),
            buttonView: Ember.View.extend({
                tagName: 'a',
                classNameBindings: ['isFocus:focus'],
                isFocus: function(){
                    return this.get('model.id') === this.get('parentView.parentView.focusId');
                }.property('parentView.parentView.focusId'),
                click: function(){
                    var self = this;
                    var fieldset = document.getElementById('fieldset-' + this.get('model.id'));
                    $(fieldset).closest('.magellan-content').animate({'scrollTop': fieldset.parentNode.offsetTop - parseFloat(getComputedStyle(fieldset).marginTop)}, 0);
                    setTimeout(function(){
                        if(self.get('parentView.parentView.focusId') !== self.get('model.id')){
                            self.get('parentView.parentView').set('focusId', self.get('model.id'));
                        }
                    }, 10);
                }
            }),
            init: function(){
                var elements = this.get('elements');
                elements = elements.filter(function(item){
                    return item.type === 'fieldset';
                });
                this.set('focusId', elements.get('firstObject.id'));
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
                        self.set('focusId', focusField.id.replace(/^fieldset-/g, ''));
                    }
                });
            }
        });
    };
});