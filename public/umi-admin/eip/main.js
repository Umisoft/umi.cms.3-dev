(function(){
    "use strict";

    var UMI = {};
    UMI.EIP = UMI.EIP || {};

    var toolbox = {
        addClass: function(el, className){
            if (el.classList){
                el.classList.add(className);
            }
            else{
                el.className += ' ' + className;
            }
            return el;
        }
    };

    UMI.EIP.toolbar = (function(){
        var toolbarTemplate = '' +
            '<ul class="umi-topbar-ul-left">' +
            '   <li><span class="umi-topbar-label"><i class="umi-topbar-icon-butterfly"></i></span></li>' +
            '   <li><span class="umi-topbar-label">UMI@@@</span></li>' +
            '   <li><a href="/admin" class="button tiny flat umi-top-bar-button">Административная панель</a></li>' +
            '</ul>' +
            '<ul class="umi-topbar-ul-right">' +
            '   <li>' +
            '       <span class="umi-button">Супервайзер' +
            '           <ul class="umi-dropdown">' +
            '               <li>' +
            '                   <a href="javascript:void(0)">Выход из системы</a>' +
            '               </li>' +
            '           </ul>' +
            '       </span>' +
            '   </li>' +
            '</ul>';
        return {
            loadDependencies: function(){

            },
            init: function(){

                var toolbar = document.createElement('div');
                toolbar.id = 'umi-eip-topbar';
                toolbar.innerHTML = toolbarTemplate;
                toolbox.addClass(document.body, 'has-umi-eip-topbar');
                document.body.appendChild(toolbar);
            }
        };
    }());

    UMI.EIP.toolbar.init();
    //window.addEventListener('load', function() {

    //}, false);
}());