define([], function(){
    'use strict';

    return function(UMI){

        UMI.ApplicationView = Ember.View.extend({
            classNames: ['umi-main-view', 's-full-height'],
            classNameBindings: ['showAllVersion'],
            showAllVersion: false,
            didInsertElement: function(){
                var self = this;
                Ember.run.next(this, function(){
                    $(document).foundation();
                });
                $(document).on('keydown', function(e){
                    if(e.altKey && e.which === 86){
                        self.toggleProperty('showAllVersion');
                    }
                });


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
                                data: {"search": search},
                                cache: false,
                                success: function(response){
                                    $(".umi-search-result").html(response);
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


                $(document).click(function(event) {
                    if ($(event.target).closest(".umi-search-component").length) return;
                    $('.umi-search-drop-down').hide();
                    $(".umi-search-input").removeClass('active');
                    event.stopPropagation();
                });

                var that = this;
                $('.umi-search-result').on('click', 'li', function(){
                    var objectId = $(this).data('object-id');
                    that.get('controller').transitionToRoute('context', objectId);
                });
            }
        });


        UMI.ToggleVersionView = Ember.View.extend({
            classNames: ['has-version'],
            actions: {
                toggleVersion: function(version){
                    var v = {};
                    v[version] = true;
                    this.set('version', v);
                }
            },
            version: {v1: true}
        });
    };
});