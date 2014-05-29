define(['App'],
    function(UMI){
        "use strict";

        return function(){
            UMI.ButtonSwitchActivityView = UMI.ButtonView.extend({
                classNames: ['umi-button-icon-32', 'umi-light-bg'],
                classNameBindings: ['object.active::umi-disabled']
            });
        };
    }
);