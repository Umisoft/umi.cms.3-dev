define(['App'], function(UMI){
    'use strict';

    return function(){

        UMI.SearchView = Ember.View.extend({
            didInsertElement: function(){

                $(".umi-search-input")
                    .mousedown(function(){
                        $('.umi-search-input').addClass('active');
                        $('.umi-search-drop-down').show();
                    })
                    .keyup(function(){
                        if($(this).val().length > 2){
                            var search = $(this).val();
                            $.ajax({
                                type: "GET",
                                url: "/admin/api/search/action/search",
                                data: {"query": search},
                                cache: false,
                                success: function(response){
                                    console.log(response);
                                    var id = '';
                                    var content = '';
                                    var l = response.result.search.length;
                                    $(".umi-search-result").html('');
                                    for(var i = 0; i < l; i++){
                                        id = response.result.search[i].id;
                                        content = response.result.search[i].displayName;
                                        $(".umi-search-result").append('<li data-object-id="' + id + '">' + content + '</li>');
                                    }
                                }
                            });
                        }
                    });

                setTimeout(function(){
                    console.log('searchResultScroll');
                    var searchResultScroll = new IScroll('.umi-search-result-wrapper', {
                        scrollX: true,
                        probeType: 3,
                        mouseWheel: true,
                        scrollbars: true,
                        bounce: false,
                        click: true,
                        freeScroll: false,
                        keyBindings: true,
                        interactiveScrollbars: true
                    });
                },0);

                $(document).click(function(event){
                    if(!$(event.target).closest(".umi-search-component").length){
                        $('.umi-search-drop-down').hide();
                        $(".umi-search-input").removeClass('active');
                        event.stopPropagation();
                    }
                });

                var that = this;
                $('.umi-search-result').on('click', 'li', function(){
                    var objectId = $(this).data('object-id');
                    that.get('controller').transitionToRoute('context', objectId);
                });
            }
        });
    };
});