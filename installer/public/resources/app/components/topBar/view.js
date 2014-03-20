define(['App'], function(UMI){
    'use strict';

    return function(){

        UMI.TopBarView = Ember.View.extend({
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
            }
        });
    };
});