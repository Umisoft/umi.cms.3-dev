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
        "alt": "alt для кнопки",
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
        "alt": "alt для кнопки",
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