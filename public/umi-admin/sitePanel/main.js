(function(){
    "use strict";

    var UMI = {};
    UMI.SITE = UMI.SITE || {};

    var toolbox = {
        addClass: function(el, className){
            if (el.classList){
                el.classList.add(className);
            }
            else{
                el.className += ' ' + className;
            }
            return el;
        },
        removeClass: function(el, className){
            if (el.classList){
                el.classList.remove(className);
            } else{
                el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
            }
            return el;
        },
        hasClass: function(el, className){
            var hasClass;
            if (el.classList){
                hasClass = el.classList.contains(className);
            } else{
                hasClass = new RegExp('(^| )' + className + '( |$)', 'gi').test(el.className);
            }
            return hasClass;
        }
    };

    UMI.SITE.toolbar = (function(){
        var toolbarTemplate = function(params){
            return '' +
            '<ul class="umi-topbar-ul-left">' +
            '   <li><span class="umi-topbar-label"><i class="umi-topbar-icon-butterfly"></i></span></li>' +
            '   <li><a href="' + params.baseURL + '" class="umi-topbar-button">Административная панель</a></li>' +
            '</ul>' +
            '<ul class="umi-topbar-ul-right">' +
            '   <li>' +
            '       <span class="umi-topbar-button-dropdown" data-umi-handler="toggleDropdown" data-umi-event-type="click">Супервайзер' +
            '           <ul class="umi-topbar-dropdown">' +
            '               <li>' +
            '                   <a href="javascript:void(0)" data-umi-handler="logout" data-umi-event-type="click"><i class="umi-topbar-icon-exit"></i> Выход из системы</a>' +
            '               </li>' +
            '           </ul>' +
            '       </span>' +
            '   </li>' +
            '</ul>';
        };
        return {
            toolbar: null,
            init: function(){
                var self = this;

                var sitePanel = document.querySelector('#umi-site-panel');

                var API = {
                    baseURL: sitePanel.getAttribute('data-baseURL'),
                    baseApiURL: sitePanel.getAttribute('data-baseApiURL')
                };
                var toolbar = document.createElement('div');
                this.toolbar = toolbar;
                toolbar.id = 'umi-site-panel-topbar';
                toolbar.innerHTML = toolbarTemplate(API);
                toolbox.addClass(document.body, 'has-umi-site-panel-topbar');
                document.body.appendChild(toolbar);

                var handlers = toolbar.querySelectorAll('[data-umi-handler]');

                var commands = {
                    logout: function(){
                        var logoutUrl = API.baseApiURL + '/action/logout';
                        var request = new XMLHttpRequest();
                        request.open('POST', logoutUrl, true);
                        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                        request.send();
                        request.onload = function(){
                            self.destroy();
                        };
                    },

                    toggleDropdown: function(){
                        var button = this;
                        if(toolbox.hasClass(button, 'umi-open')){
                            toolbox.removeClass(button, 'umi-open');
                            document.body.removeEventListener('click', commands.closeDropdown, false);
                        } else{
                            toolbox.addClass(button, 'umi-open');
                            setTimeout(function(){
                                document.body.addEventListener('click', commands.closeDropdown, false);
                            }, 10);
                        }
                    },

                    closeDropdown: function(){
                        var dropDownButtons = toolbar.querySelectorAll('.umi-topbar-button-dropdown');
                        for(var i = 0; i < dropDownButtons.length; i++){
                            if(toolbox.hasClass(dropDownButtons[i], 'umi-open')){
                                toolbox.removeClass(dropDownButtons[i], 'umi-open');
                            }
                        }
                        document.body.removeEventListener('click', commands.closeDropdown, false);
                    }
                };

                function callCommand(el){
                    var command = el.getAttribute('data-umi-handler');
                    if(command in commands){
                        return commands[command];
                    }
                }

                for(var i = 0; i < handlers.length; i++){
                    handlers[i].addEventListener(handlers[i].getAttribute('data-umi-event-type'), callCommand(handlers[i]), false);
                }
            },

            destroy: function(){
                document.location.href = document.location.href;
            }
        };
    }());

    UMI.SITE.toolbar.init();
}());