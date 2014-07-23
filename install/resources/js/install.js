var UMI = UMI || {};

(function () {
    var hashList = [];
    var stepsInfo = {};
    var pathInstaller = '';

    $(function () {
        $.get(pathInstaller, {'command': 'getStepsInfo'}).then(function (data) {
            stepsInfo = data;

            showCurrentStep();

            $('.js-handler-step-next').on('click', function (e) {
                nextStep();
                return false;
            });

            $('.js-handler-step-back').on('click', function (e) {
                backStep();
                return false;
            });

            $('.js-handler-license-key').on('keyup', function (e) {
                if (e.target.value.length > 0) {
                    $('.js-handler-step-next')
                        .removeAttr('disabled')
                        .removeClass('back_step_submit')
                        .addClass('next_step_submit');
                }
            });

            $('.js-handler-getTrial').on('click', function (e) {
                setHashCommand('trial');
                showCurrentStep();
                return false;
            });

            $('.js-handler-log').on('click', function () {
                $('.js-handler-install-log').toggle(
                    function () {
                        $('.js-handler-install-log').removeClass('display_none')
                    }, function () {
                        $('.js-handler-install-log').addClass('display_none')
                    }
                )
            });

            $(document).on('click', '.js-handler-repeat', function(e) {
                this.remove();
                var params = {};
                params.command = hashList.command;
                stepRequest(pathInstaller, params);
            });

            $(document).on('click', '.js-handler-changeAuthData', function(e) {
                var params = {};
                params.command = hashList.command;
                params.changeAuthData = {};
                params.changeAuthData.login = $('.js-handler-changeAuthData-login').val();
                params.changeAuthData.email = $('.js-handler-changeAuthData-email').val();
                stepRequest(pathInstaller, params);
                overlay.close();
            });

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

        });


    });

    var nextStep = function () {
        var step = hashList.command || 'checkLicense';
        var params = UMI.registry.getCommand(step).execute(step);

        $.get(pathInstaller, params).then(function (data) {
            switch (stepsInfo.result[step].nextStep) {
                case 'checkDb' :
                {
                    checkDb();
                    break;
                }
                case 'checkLicense' : {
                    if (data.result.licenseKey !== undefined) {
                        $('.js-handler-license-key').val(data.result.licenseKey);
                        $('.js-handler-step-next')
                            .removeAttr('disabled')
                            .removeClass('back_step_submit')
                            .addClass('next_step_submit');
                    }
                }
                default :
                {
                    if (stepsInfo.result[step].nextStep !== undefined) {
                        setHashCommand(stepsInfo.result[step].nextStep);
                    }
                    showCurrentStep();
                }
            }
        }, function (error) {
            $('.js-step-' + step +' .info').removeClass('display_none');
            $('.js-step-' + step + ' .img_stop img').attr("src", "http://install.umi-cms.ru/icon_stop_red.png");
            $('.js-step-' + step + ' .img_stop_text').html(error.responseJSON.message);
        });
    };

    var backStep = function () {
        var step = hashList.command || 'checkLicense';
        if (stepsInfo.result[step].prevStep !== undefined) {
            setHashCommand(stepsInfo.result[step].prevStep);
            showCurrentStep();
        }
    };

    var getHashList = function () {
        $.each(window.location.hash.replace("#", "").split("&"), function (i, value) {
            value = value.split("=");
            hashList[value[0]] = value[1];
        });
    };

    var setHashCommand = function (name) {
        window.location.hash = 'command=' + name;
        getHashList();
    };

    var showCurrentStep = function () {
        getHashList();
        if (hashList.command !== undefined) {
            $('.shadow_some').addClass('display_none');
            $('.js-step-' + hashList.command).removeClass('display_none');

            $('.js-handler-stepName').text(stepsInfo.result[hashList.command].title)

            var stepListElement = $('li[class^="js-step-nav-"]');
            $(stepListElement).removeClass('list_style_nonetwo');
            $(stepListElement).find('div').removeClass('color_dif');

            if (hashList.command === 'db') {
                setDbConfig();
            }

            if (hashList.command !== 'checkLicense' && hashList.command !== 'trial') {
                for (var i = 0; i < stepListElement.length; i++ ){
                    $(stepListElement[i]).addClass('list_style_nonetwo');
                    $(stepListElement[i]).find('div').addClass('color_dif');
                    if (hashList.command === 'finish' && i === stepListElement.length - 1) {
                        $(stepListElement[i]).removeClass('list_style_noneleft');
                        $(stepListElement[i]).addClass('list_style_noneright');
                    }
                    if ($(stepListElement[i]).hasClass('js-step-nav-' + hashList.command)) {
                        break;
                    }
                }
            }

            if (stepsInfo.result[hashList.command].stepNum !== undefined) {
                $('.js-handler-stepNum').text('Шаг ' + stepsInfo.result[hashList.command].stepNum + ' из ' + stepsInfo.result.allSteps);
            }
        }
    };

    // Проверка БД и запуск автоматической установки системы
    var setDbConfig = function () {
        var params = {};
        params.command = 'getDbConfig';
        $.get(pathInstaller, params).then(function (data) {
            $('.js-handler-db-dbname').val(data.result.dbname);
            $('.js-handler-db-host').val(data.result.host);
            $('.js-handler-db-login').val(data.result.login);
            $('.js-handler-db-password').val(data.result.password);
        })
    };

    var checkDb = function () {
        var params = {};
        var step = hashList.command || 'checkDb';
        params.command = stepsInfo.result[step].nextStep;

        $.get(pathInstaller, params).then(function (data) {
            setHashCommand(stepsInfo.result[params.command].nextStep);
            showCurrentStep();
            startInstall();
        }, function (error) {
            $('.js-step-' + hashList.command +' .info').removeClass('display_none');
            $('.js-step-' + hashList.command + ' .img_stop img').attr("src", "http://install.umi-cms.ru/icon_stop_red.png");
            $('.js-step-' + hashList.command + ' .img_stop_text').html(error.responseJSON.message);
        });
    };

    var startInstall = function () {
        $('.img_stop img').attr("src", "http://install.umi-cms.ru/ikon_stop.png");
        $('.img_stop_text').html('');
        var params = {};
        params.command = hashList.command;
        stepRequest(pathInstaller, params);
    };

    function stepRequest(path, params) {
        var promise = $.get(path, params);
        showProcessLog(hashList.command);
        showCurrentStep();
        return promise.then(
            function (data) {
                var nextStep = stepsInfo.result[hashList.command].nextStep;
                if (nextStep !== 'finish') {
                    setHashCommand(nextStep);
                    params.command = nextStep;
                    return stepRequest(path, params);
                } else if (nextStep === 'finish') {
                    setHashCommand(nextStep);
                    showCurrentStep();
                }
            }, function (error) {
                $('<p style="color:red">' + error.responseJSON.message + '<a class="js-handler-repeat">Попробовать еще раз</a></p>').prependTo('.js-handler-scroll-panel');

                if (error.responseJSON.overlay !== undefined && error.responseJSON.overlay !== null) {
                    overlay.open('<p style="color:red">' + error.responseJSON.message + '</p>' + error.responseJSON.overlay)
                }
            }
        );
    }

    var showProcessLog = function (step) {
        $('<p>' + stepsInfo.result[step].title + '</p>').prependTo('.js-handler-scroll-panel');
    }

    var Overlay = function (){
        this.open = function (content) {
            var $overlay = $('.js-handler-overlay');
            $overlay.html(content);
            $overlay.addClass('open');
        }

        this.close = function () {
            var $overlay = $('.js-handler-overlay');
            $overlay.html();
            $overlay.removeClass('open');
        }
    };

    var overlay = new Overlay();
}());