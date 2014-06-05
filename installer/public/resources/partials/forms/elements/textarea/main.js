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
                $('body').on('mousedown', '.umi-element-textarea-resizer', function(event){
                    $('html').addClass('s-unselectable');
                    $(this).addClass('s-unselectable');
                    if(event.button === 0){
                        var $that = $(this);
                        var posY = $(this).parent().find('textarea').offset().top;

                        $('html').addClass('s-unselectable');
                        $('body').mousemove(function(event){

                            //TODO Допилить и сделать метод глобальным
                            //Подумать над тем, чтобы выделение сделаное до mouseMove не слетало
                            //http://hashcode.ru/questions/86466/javascript-%D0%BA%D0%B0%D0%BA-%D0%B7%D0%B0%D0%BF%D1%80%D0%B5%D1%82%D0%B8%D1%82%D1%8C-%D0%B2%D1%8B%D0%B4%D0%B5%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5-%D1%81%D0%BE%D0%B4%D0%B5%D1%80%D0%B6%D0%B8%D0%BC%D0%BE%D0%B3%D0%BE-%D0%BD%D0%B0-%D0%B2%D0%B5%D0%B1%D1%81%D1%82%D1%80%D0%B0%D0%BD%D0%B8%D1%86%D0%B5
                            var removeSelection = function(){
                                if (window.getSelection) { window.getSelection().removeAllRanges(); }
                                else if (document.selection && document.selection.clear)
                                    document.selection.clear();
                            };
                            removeSelection();

                            var h = event.pageY - posY;
                            if(h < 60){h = 60}
                            $that.parent().find('textarea').css({height: h});

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
                    return Ember.Handlebars.compile('{{textarea value=object.' + dataSource + ' placeholder=meta.placeholder validator="collection" name=meta.attributes.name}}');
                }.property()
            })
        });

    };
});