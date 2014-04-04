define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.SeoMegaIndexView = Ember.View.extend({
            templateName: 'seoMegaIndex',
            classNames: ['umi-table-ajax'],
            headers: [],
            data: [],

            didInsertElement: function(){
                var that = this;

                var megaIndexScroll = new IScroll('.umi-table-ajax-control-content-center', UMI.config.iScroll);

                //Получаем список счётчиков
                (function getCounters(){
                    var path = '/admin/api/seo/megaindex/action/siteAnalyze'; //Приходит в http://localhost/admin/api/seo/megaindex
                    $.ajax({
                        type: "GET",
                        url: path,
                        data: {},
                        cache: false,

                        beforeSend: function(){
                            $('.umi-component').css({'position':'relative'}).append(function(){
                                return '<div class="umi-loader" style="position: absolute; overflow: hidden; z-index: 1; padding: 30px; width: 100%; height: 100%; background: #FFFFFF;"><i class="animate animate-loader-40"></i><h3>Идёт загрузка данных...</h3></div>';
                            });
                        },

                        success: function(response){
                            $('.umi-loader').remove();
                            response.result.siteAnalyze.data.length = 100;
                            console.log('ARRAY:', response.result.siteAnalyze.data);
                            that.set('headers', response.result.siteAnalyze.data.shift());
                            that.set('data', response.result.siteAnalyze.data);

                            setTimeout(function(){
                                megaIndexScroll.refresh();
                            }, 200);
                        },

                        error: function(code){
                            $('.umi-loader').remove();
                            $('.umi-component').css({'position':'relative'}).append(function(){
                                return '<div class="umi-loader" style="position: absolute; padding: 30px; background: rgba(214,224,233,.7);"><h3>Не удалось загрузить данные</h3></div>';
                            });
                        }
                    });
                })();
            },

            willClearRender: function(){
                console.log('clearRender');
//                megaIndexScroll.destroy();
//                megaIndexScroll = null;
            }
        });
    };
});