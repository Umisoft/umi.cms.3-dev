(function(){
    "use strict";

    var UMI = {};
    UMI.SITE = UMI.SITE || {};

    var toolbox = {
        get: function(object, path){
            function _get(obj, path, i){
                if(Object.prototype.toString.call(obj).slice(8, -1) === 'Object' && path){
                    if(i < path.length - 1){
                        return _get(obj[path[i]], path, 1 + i);
                    } else{
                        return obj[path[i]];
                    }
                } else{
                    return undefined;
                }
            }
            path = path.split('.');
            var i = 0;
            var propertyValue = _get(object, path, i);
            return propertyValue;
        },
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
            '   <li><a href="' + params.baseURL + '" class="umi-topbar-button">' + toolbox.get(params, 'i18n.adminPanelLabel') + '</a></li>' +
            '</ul>' +
            '<ul class="umi-topbar-ul-right"></ul>';
        };

        var userDropdown = function(params){
            return '<li>' +
            '<span class="umi-topbar-button-dropdown" data-umi-handler="toggleDropdown">' + toolbox.get(params, 'user.displayName') +
            '    <ul class="umi-topbar-dropdown">' +
            '         <li>' +
            '             <a href="javascript:void(0)" data-umi-handler="logout"><i class="umi-topbar-icon-exit"></i> ' + toolbox.get(params, 'i18n.logoutLabel') + '</a>' +
            '         </li>' +
            '    </ul>' +
            '</span></li>';
        };
        return {
            toolbar: null,
            API: null,
            loadAPI: function(){
                var self = this;
                var API = window.UmiSettings;
                self.API = API;
                self.init();
                var request = new XMLHttpRequest();
                request.open('GET', toolbox.get(API, 'baseApiURL') + '/action/auth', true);
                request.onload = function(){
                    if (request.status >= 200 && request.status < 400){
                        var result = JSON.parse(request.responseText) || {};
                        API.user = toolbox.get(result, 'result.auth.user');
                        self.API = API;
                        self.toolbar.querySelector('.umi-topbar-ul-right').innerHTML = userDropdown(self.API);
                    } else {
                        // We reached our target server, but it returned an error
                    }
                };

                request.onerror = function() {
                    // There was a connection error of some sort
                };

                request.send();
            },
            init: function(){
                var self = this;

                var toolbar = document.createElement('div');
                this.toolbar = toolbar;
                toolbar.id = 'umi-site-panel-topbar';
                toolbar.innerHTML = toolbarTemplate(self.API);
                toolbox.addClass(document.body, 'has-umi-site-panel-topbar');
                document.body.appendChild(toolbar);

                var commands = {
                    logout: function(){
                        var logoutUrl = self.API.baseApiURL + '/action/logout';
                        var request = new XMLHttpRequest();
                        request.open('POST', logoutUrl, true);
                        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                        request.send();
                        request.onload = function(){
                            self.destroy();
                        };
                    },

                    toggleDropdown: function(el){
                        var button = el;
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

                    closeDropdown: function(el){
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
                        commands[command](el);
                    }
                }

                document.body.addEventListener('click', function(event){
                    event = event || window.event;
                    var target = event.target || event.srcElement;
                    while(target !== this){
                        if(target.hasAttribute('data-umi-handler')){
                            callCommand(target);
                            break;
                        }
                        target = target.parentNode;
                    }
                }, false);
            },

            destroy: function(){
                document.location.href = document.location.href;
            }
        };
    }());

    UMI.SITE.toolbar.loadAPI();
}());