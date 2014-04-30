define(['App', 'text!./textareaElement.hbs'], function(UMI, textareaElement){
    "use strict";

    Ember.TEMPLATES['UMI/components/textarea-element'] = Ember.Handlebars.compile(textareaElement);

    //TODO Запилить нормальную тянучку
    return function(){

        UMI.TextareaElementComponent = Ember.Component.extend(UMI.InputValidate, {
            value: '',
            classNames: ['umi-element-textarea'],

            didInsertElement: function(){
                this.allowResize();
            },

            /*TODO
            * отключать обработчики
            * textarea неверной высоты при загрузке - должно быть 60px
            * */
            allowResize: function(){
                var that = this.$();
                $('body').on('mousedown', '.umi-element-textarea-resizer', function(event){
                    if(event.button === 0){
                        var posY = that.find('textarea').offset().top;

                        $('html').addClass('s-unselectable');
                        $('body').mousemove(function(event){
                            var h = event.pageY - posY;

                            if(h < 60){h = 60}

                            that.find('textarea').css({height: h});

                            $('body').on('mouseup', function(){
                                $('body').off('mousemove');
                                $('html').removeClass('s-unselectable');
                            });
                        });
                    }
                });
            },

            textareaView: Ember.View.extend({
                template: function(){
                    var dataSource = this.get('parentView.meta.dataSource');
                    return Ember.Handlebars.compile('{{textarea value=object.' + dataSource + ' placeholder=meta.placeholder validator="collection"}}');
                }.property()
            })
        });

    };
});