<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\surveys\model\collection;

use umi\form\element\CheckboxGroup;
use umi\form\element\Radio;
use umi\form\element\Submit;
use umi\form\IForm;
use umi\form\IFormAware;
use umi\form\TFormAware;
use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\CmsPageCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\surveys\model\object\Answer;
use umicms\project\module\surveys\model\object\Survey;

/**
 * Коллекция для работы с опросами.
 *
 * @method CmsSelector|Survey[] select() Возвращает селектор для выбора опросов.
 * @method Survey get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает опрос по его GUID.
 * @method Survey getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает опрос по его id.
 * @method Survey add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает опрос.
 */
class SurveyCollection extends CmsPageCollection implements IFormAware
{
    use TFormAware;

    /**
     * Возвращает форму голосования для опроса.
     * @param Survey $survey
     * @return IForm
     */
    public function getVoteForm(Survey $survey)
    {

        $form = $this->createForm([
                'options' => [
                    'dictionaries' => [
                        'collection.survey', 'collection', 'form'
                    ],
                ],
                'attributes' => [
                    'method' => 'post'
                ]
            ]);

        $answersElementType = $survey->multipleChoice ? CheckboxGroup::TYPE_NAME : Radio::TYPE_NAME;

        $answers = [];
        /**
         * @var Answer $answer
         */
        foreach ($survey->answers as $answer) {
            $answers[$answer->guid] = $answer->displayName;
        }

        $answersElement = $this->createFormEntity(

            Survey::FIELD_ANSWERS,
            [
                'type' => $answersElementType,
                'label' => Survey::FIELD_ANSWERS,
                'options' => ['choices' => $answers]
            ]
        );

        $form->add($answersElement);
        $submit = $this->createFormEntity('submit', ['type' => Submit::TYPE_NAME, 'label' => 'Vote']);
        $form->add($submit);

        return $form;
    }
}
