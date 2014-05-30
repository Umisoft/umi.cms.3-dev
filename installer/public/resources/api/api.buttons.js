/**
 * Простая кнопка
 * @type {{type: string, behaviour: string, displayName: string, attributes: {title: string, alt: string, class: string[]}, hasIcon: boolean}}
 */
var button = {
    "type": "button",
    "behaviour": "delete",
    "displayName": "Удалить",
    "attributes": {
        "title": "Удаление новости",
        "alt": "alt для кнопки",
        "class": ["large", "primary"]
    },
    "hasIcon": true
};

/**
 * Кнопка с выпадающим списком
 * @type {{type: string, displayName: string, attributes: {title: string, alt: string, class: string[]}, list: *[]}}
 */
var dropDownButton = {
    "type": "dropDownButton",
    "displayName": "Создать",
    "attributes": {
        "title": "Создать",
        "alt": "alt для кнопки",
        "class": ["large", "secondary"]
    },
    "list": [
        {"displayName": "Создать новость", "behaviour": "create"},
        {"displayName": "Создать рубрику", "behaviour": "create"}
    ]
};