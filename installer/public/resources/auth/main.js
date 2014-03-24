define(['auth/templates', 'Handlebars', 'jQuery'], function(tempaltes){
    "use strict";

    return function(){
        var Auth = {
            TEMPLATES: {},
            forms: {},
            getForm: function(action){
                var deferred = $.Deferred();
                action =  action || 'form';
                var self = this;
                $.get(window.UmiSettings.baseApiURL + '/action/' + action).then(function(results){
                    self.forms[action] = results.result.form;
                    deferred.resolve();
                });
                return deferred;
            },
            validator: {
                shake: function(){
                    function shake(id, a, d){
                        id.style.left = a.shift() + 'px';
                        if(a.length > 0){
                            setTimeout(function(){
                                shake(id, a, d);
                            }, d);
                        }
                    }

                    var p = [10, 20, 10, 0, -10, -20, -10, 0];
                    p = p.concat(p.concat(p));
                    var i = document.getElementsByClassName('shake-it').item(0);
                    i.style.position = 'relative';
                    shake(i, p, 20);
                },
                check: function(form){
                    var i;
                    var element;
                    var pattern;
                    var valid = true;
                    var removeError = function(el){
                        el.onfocus = function(){
                            $(this).closest('.columns').removeClass('error');
                        };
                    };
                    for(i = 0; i < form.elements.length; i++){
                        element = form.elements[i];
                        if((element.hasAttribute('required') || element.value) && element.hasAttribute('pattern')){
                            pattern = new RegExp(element.getAttribute('pattern'));
                            if(!pattern.test(element.value)){
                                valid = false;
                            }
                        } else if(element.hasAttribute('required')){
                            if(!element.value){
                                valid = false;
                            }
                        }
                        if(element.getAttribute('type') !== 'submit'){
                            if(valid){
                                $(element).closest('.columns').removeClass('error');
                                element.onfocus = null;
                            } else{
                                $(element).closest('.columns').addClass('error');
                                removeError(element);
                                return false;
                            }
                        }
                    }
                    return true;
                }
            },
            transition: function(){
                // Событие при успешном переходе
                document.onmousemove = null;
                var authLayout = document.querySelector('.auth-layout');
                var maskLayout = document.querySelector('.auth-mask');
                $(authLayout).addClass('off');
                require(['app/main', 'DS', 'Modernizr', 'Foundation', 'iscroll', 'ckEditor', 'elFinder', 'datepicker', 'moment'], function(application){
                    application();
                    $(authLayout).addClass('fade-out');
                    setTimeout(function(){
                        authLayout.parentNode.removeChild(authLayout);
                        maskLayout.parentNode.removeChild(maskLayout);
                        //Auth = null; TODO: Нужно удалять приложение Auth после авторизации
                    }, 2000);
                });
            },
            init: function(){
                tempaltes(Auth);
                this.getForm().then(function(){
                    // Проверяем есть ли шаблон и если нет то собираем его
                    if(!document.querySelector('.auth-layout')){
                        var helper = document.createElement('div');
                        helper.innerHTML = Auth.TEMPLATES.app({outlet: Auth.TEMPLATES.index({form: Auth.forms.form})});
                        helper = document.body.appendChild(helper);
                        $(helper.firstElementChild).unwrap();
                    }

                    var bubbles = document.querySelector('.bubbles');
                    var bubblesFront = document.querySelector('.bubbles-front');
                    var parallax = function(event){
                        var bodyWidth = document.body.offsetWidth;
                        var bodyHeight = document.body.offsetHeight;
                        var deltaX = 0.04;
                        var deltaY = 0.04;
                        var left = (bodyWidth / 2 - event.clientX) * deltaX;
                        var top = (bodyHeight / 2 - event.clientY) * deltaY;
                        bubbles.style.marginLeft = left + 'px';
                        bubbles.style.marginTop = top + 'px';
                        bubblesFront.style.marginLeft = left * 0.2 + 'px';
                        bubblesFront.style.marginTop = top * 0.2 + 'px';
                    };

                    var bubblesHidden = true;
                    document.onmousemove = function(event){
                        if(bubblesHidden){
                            bubbles.className = 'bubbles visible';
                            bubblesFront.className = 'bubbles-front visible';
                            bubblesHidden = false;
                        }
                        parallax(event);
                    };


                    $(document).on('click', '.close', function(){
                        this.parentNode.parentNode.removeChild(this.parentNode);
                        return false;
                    });

                    var errorsBlock = document.querySelector('.errors-list');

                    $(document).on('submit.umi.auth', 'form', function(){
                        if(!Auth.validator.check(this)){
                            return false;
                        }
                        var container = $(this.parentNode);
                        container.addClass('loading');
                        var submit = this.elements.submit;
                        submit.setAttribute('disabled', 'disabled');
                        var data = $(this).serialize();
                        var action = this.getAttribute('action');
                        var deffer = $.post(action, data);
                        deffer.done(function(data){
                            Auth.transition();
                        });
                        deffer.fail(function(error){
                            container.removeClass('loading');
                            submit.removeAttribute('disabled');
                            if(error.status === 401){
                                // console.log(error, sad, asdsad, error.responseJSON);
                                var errorList = {error: error.responseJSON.result.error.message};
                                errorsBlock.innerHTML = Auth.TEMPLATES.errors(errorList);
                                $(errorsBlock).children('.alert-box').addClass('visible');
                            }
                        });
                        return false;
                    });
                });
            }
        };

        Auth.init();
    };
});