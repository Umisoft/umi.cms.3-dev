<!DOCTYPE HTML>
<html data-foundation>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>UMI.CMS 3 | Авторизация</title>

        <link rel="stylesheet" type="text/css" href="/resources/deploy/app.css">
         <!-- Подключение тестов TODO сделать отключения для продакшн -->
        <link rel="stylesheet" href="/resources/libs/qunit/qunit/qunit.css">
        <script>
            window.UmiSettings = {
                "login": true,
                "rootURL": '/admin/'
            }
        </script>
    </head>
    <body id="body">
        <noscript>
            NO JS
        </noscript>

        <script type="text/template" id="auth">
            <div class="auth-layout">
                <div class="bubbles"></div>
                <div class="bubbles-front"></div>
                <div class="row vertical-center">
                    <div class="large-centered columns large-3">
                        <p class="text-center">
                            <img src="resources/deploy/img/auth-logo.png" />
                        </p>
                        <div>
                            <div class="panel pretty radius shake-it">
                                <form>
                                    <div class="errors-list"></div>
                                    <div class="row">
                                        <div class="small-12 columns">
                                            <i class="icon icon-user input-icon"></i>
                                            <input type="text" name="login" placeholder="Введите логин"/>
                                            <span class="error">Поле не заполнено</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="small-12 columns">
                                            <i class="icon icon-key input-icon"></i>
                                            <input type="text" name="password" placeholder="Введите пароль"/>
                                            <span class="error">Поле не заполнено</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="small-7 columns">
                                            <label class="left">
                                                <input type="checkbox" name="rememberMe"/>
                                                Запомнить меня
                                            </label>
                                        </div>
                                        <div class="small-5 columns">
                                            <input type="submit" value="Войти">
                                            <span class="loader"></span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="text-center">
                                <a href="lostPassword" class="button simple" data-handler="forgetPass">Забыли пароль?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="auth-mask"></div>
        </script>



        <!-- if auth -->

        <!-- else -->
         <!--script data-main="resources/main" src="resources/libs/requirejs/require.js"></script-->

         <!-- tests -->
         <style>
            .qunit-container-button{
                position: absolute;
                z-index: 100000;
                top: 0;
                left: 0;
                height: 25px;
                background: #008000;
                color: #FFFFFF;
                line-height: 25px;
                padding: 0 5px;
                font-size: 13px;
            }
            .qunit-container{
                display:none;
                position: absolute;
                z-index: 100000;
                width: 100%;
                top: 24px;
                left: 0;
                border-bottom: 10px solid rgba(0, 0, 0, 0.73);
            }
        </style>

        <div class="qunit-container-button">Результаты тестов</div>
        <div class="qunit-container">
            <div id="qunit"></div>
            <div id="qunit-fixture"></div>
        </div>

        <!--script src="resources/libs/qunit/qunit/qunit.js"></script-->
        <!--script src="resources/tests-front/test-main.js"></script-->

        <script data-main="/resources/main" src="/resources/libs/requirejs/require.js"></script>
    </body>
</html>