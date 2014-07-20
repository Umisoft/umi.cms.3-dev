var UMI = UMI || {};

(function(){
    // Command: абстрактная Команда
    function Command() {
        this.execute = function () {
        };
    }

    // CheckLicense: Проверка лицензии
    function CheckLicense() {
        this.execute = function (step) {
            var params = {};
            params.command = step;
            params.licenseKey = $('.js-handler-license-key').val();
            return params;
        };
    }
    CheckLicense.prototype = new Command();

    // ProjectType: Проверка типа проекта
    function ProjectType() {
        this.execute = function (step) {
            var params = {};
            params.command = step;
            params.projectType = $('.js-handler-select-typeProject').val();
            return params;
        };
    }
    ProjectType.prototype = new Command();

    // SaveDbConfig: Сохраняет данные подключения к БД
    function SaveDbConfig() {
        this.execute = function(step) {
            var params = {};
            params.command = step;
            params.db = {};
            params.db.dbname = $('.js-handler-db-dbname').val();
            params.db.host = $('.js-handler-db-host').val();
            params.db.login = $('.js-handler-db-login').val();
            params.db.password = $('.js-handler-db-password').val();
            return params;
        }
    }
    SaveDbConfig.prototype = new Command();

    // SaveSiteAccess: Сохраняет данные входа в панель управления
    function SaveSiteAccess() {
        this.execute = function(step) {
            var params = {};
            params.command = step;
            params.siteAccess = {};
            params.siteAccess.login = $('.js-handler-siteAccess-login').val();
            params.siteAccess.email = $('.js-handler-siteAccess-email').val();
            params.siteAccess.password = $('.js-handler-siteAccess-password').val();
            params.siteAccess.password2 = $('.js-handler-siteAccess-password2').val();
            return params;
        }
    }
    SaveSiteAccess.prototype = new Command();

    // GetLicenseKey: Получает триальную лицензию
    function GetLicenseKey() {
        this.execute = function(step) {
            var params = {};
            params.command = step;
            params.trial = {};
            params.trial.lname = $('.js-handler-trial-lname').val();
            params.trial.fname = $('.js-handler-trial-fname').val();
            params.trial.email = $('.js-handler-trial-email').val();
            return params;
        }
    }
    SaveSiteAccess.prototype = new Command();

    // Registry: Регистр команд
    UMI.registry =
    {
        // private attributes
        commandsList : {},

        // public methods
        setCommand : function(_name, _value)
        {
            this.commandsList[_name] = _value;
        },
        getCommand : function(_name)
        {
            return this.commandsList[_name];
        }


    }

    UMI.registry.setCommand("checkLicense", new CheckLicense());
    UMI.registry.setCommand("projectType", new ProjectType());
    UMI.registry.setCommand("db", new SaveDbConfig());
    UMI.registry.setCommand("siteAccess", new SaveSiteAccess());
    UMI.registry.setCommand("trial", new GetLicenseKey());
}());