define(['App', 'text!./search.hbs'], function(UMI, searchTpl){
        'use strict';
        Ember.TEMPLATES['UMI/components/search-content'] = Ember.Handlebars.compile(searchTpl);

        UMI.SearchContentComponent = Ember.Component.extend({
            tagName: 'form',
            submit: function(){
                var form = this.$();
                var textField = form[0].elements[0];
                var i;
                var self = this;

                var result = [
                    {
                        'title': 'О нас',
                        'text': 'Сами разработчики UMI.CMS поставили «легкость» работы с системой в качестве одного из ' + 'основных концептуальных положений. Система изначально была написана на PHP5 (начинала разрабатываться она в 2004 г.,' + ' когда его еще толком никто не использовал). Система создавалась от начала и до конца одной командой программистов. ' + 'Это можно отметить как большой плюс: действующие разработчики понимают систему полностью «изнутри».',
                        'slug': '2'
                    },
                    {
                        'title': 'Контакты',
                        'text': 'Телефон в Москве: +7 (495) 662-87-59 Телефон в Cанкт-Петербурге: +7 (812) 309-03-15' + ' Бесплатный телефон для городов России: 8-800-5555-864 (для некоторых операторов +7-800-5555-864)',
                        'slug': '11'
                    }
                ];
                for(i = 0; i < form[0].elements.length; i++){
                    form[0].elements[i].setAttribute('disabled', 'disabled');
                }
                this.set('isProcess', true);

                // нужно бы красиво накрутить счетчик
                var counter = 146;

                function putinFunction(){
                    if( counter === 146 ){
                        counter = 0;
                    }
                    if( counter < 100 ){
                        counter++;
                        self.set('percentComplete', counter);
                        window.setTimeout(putinFunction, 30);
                    }
                    if( counter === 100 ){
                        self.set('isProcess', false);
                        self.set('percentComplete', 0);
                        self.set('results', result);
                    }
                }

                putinFunction();
                return false;
            },
            isProcess: false,
            percentComplete: 0,
            results: []
        });

        UMI.ProgressBarComponent = Ember.Component.extend({
            template: Ember.Handlebars.compile('<div class="row"><div class="small-12"><div class="progress small-2 small-offset-2"><span class="meter" {{bind-attr style=widthElement}}></span></div>'),
            widthElement: function(){
                return 'width:' + this.get('percent') + '%';
            }.property('percent')
        });
    });