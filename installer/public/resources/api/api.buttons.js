/**
 * Простая кнопка
 * @type {{type: string, behaviour: {name: string}, attributes: {title: string, alt: string, class: string, label: string, icon: {class: string}}}}
 */
var button = {
    "type": "button",
    "behaviour": {
        "name": "delete"
    },
    "attributes": {
        "title": "Удаление новости",
        "class": "large primary",
        "label": "Удалить",
        "icon": {
            "class": "icon icon-delete"
        }
    }
};

/**
 * Кнопка с выпадающим списком
 * @type {{type: string, attributes: {title: string, alt: string, class: string, label: string, icon: {class: string}}, choices: *[]}}
 */
var dropDownButton = {
    "type": "dropDownButton",
    "attributes": {
        "title": "Создать",
        "class": "large secondary",
        "label": "Создать",
        "icon": {
            "class": "icon icon-delete"
        }
    },
    "choices": [
        {
            "behaviour": {
                "name": "create",
                "typeName": "base"
            },
            "attributes": {
                "label": "Создать новость"
            }
        }
    ]
};

/**
 * Пример контролов для Table Control
 * @type {{toolbar: *[], contextToolbar: *[]}}
 */
var TableControl = {
    "toolbar": [
        {
            "type": "button",
            "behaviour": {
                "name": "create"
            },
            "attributes": {
                "title": "Добавить рубрику",
                "class": "large primary",
                "label": "Добавить рубрику",
                "icon": {
                    "class": "icon icon-create"
                }
            }
        }
    ],
    "contextToolbar": [
        {
            "type": "dropDownButton",
            "attributes": {
                "class": "umi-button umi-toolbar-create-button"},
            "choices": [
                {"behaviour": {"name": "create", "typeName": "base"}, "attributes": {"title": "Добавить рубрику", "label": "Добавить рубрику"}},
                {"behaviour": {"name": "switchActivity"}, "attributes": {"title": "Сменить активность", "label": "Сменить активность"}},
                {"behaviour": {"name": "viewOnSite"}, "attributes": {"title": "Посмотреть на сайте", "label": "Посмотреть на сайте"}}
            ]
        }
    ]
};

/**
 * Пример контролов для Tree Control
 * @type {{contextToolbar: *[]}}
 */
var TreeControl = {
    "contextToolbar": [
        {
            "type": "dropDownButton",
            "attributes": {
                "class": "umi-button umi-toolbar-create-button"},
            "choices": [
                {"behaviour": {"name": "create", "typeName": "base"}, "attributes": {"title": "Добавить рубрику", "label": "Добавить рубрику"}},
                {"behaviour": {"name": "switchActivity"}, "attributes": {"title": "Сменить активность", "label": "Сменить активность"}},
                {"behaviour": {"name": "viewOnSite"}, "attributes": {"title": "Посмотреть на сайте", "label": "Посмотреть на сайте"}}
            ]
        }
    ]
};

/**
 * Пример контролов для Form Control
 * @type {{toolbar: *[]}}
 */
var FormControl = {
    "toolbar": [
        {
            "type": "button",
            "behaviour": {
                "name": "backToFilter"
            },
            "attributes": {
                "title": "Вернуться к списку",
                "class": "umi-button-icon-32 umi-light-bg",
                "icon": {
                    "class": "icon icon-backToFilter"
                }
            }
        },
        {
            "type": "button",
            "behaviour": {
                "name": "viewOnSite"
            },
            "attributes": {
                "title": "Посмотреть на сайте",
                "class": "umi-button-icon-32 umi-light-bg",
                "icon": {
                    "class": "icon icon-viewOnSite"
                }
            }
        },
        {
            "type": "button",
            "behaviour": {
                "name": "switchActivity"
            },
            "attributes": {
                "title": "Изменить активность",
               "class": "umi-button-icon-32 umi-light-bg",
                "icon": {
                    "class": "icon icon-switchActivity"
                }
            }
        },
        {
            "type": "button",
            "behaviour": {
                "name": "trash"
            },
            "attributes": {
                "title": "Удалить в корзину",
                "class": "umi-button-icon-32 umi-light-bg",
                "icon": {
                    "class": "icon icon-trash"
                }
            }
        },
        {
            "type": "dropDownButton",// Новый тип
            "behaviour": {
                "name": "backupList"
            },
            "attributes": {
                "title": "Список бэкапов",
                "class": "umi-button-icon-32 umi-light-bg",
                "icon": {
                    "class": "icon icon-backupList"
                }
            },
            "choices": [
                {"behaviour": {"name": "getBackup"}, "attributes": {"label": "2014-05-29 02:30:54 Имя пользователя"}}
            ]
        }
    ]
};