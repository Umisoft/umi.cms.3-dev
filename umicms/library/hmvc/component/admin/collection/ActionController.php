<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\admin\collection;

use umi\form\element\Checkbox;
use umi\form\element\html5\DateTime;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\element\Textarea;
use umi\form\IFormAware;
use umi\form\IFormEntity;
use umi\form\TFormAware;
use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umi\i18n\translator\ITranslator;
use umi\orm\collection\ISimpleHierarchicCollection;
use umi\orm\metadata\field\datetime\DateTimeField;
use umi\orm\metadata\field\IField;
use umi\orm\metadata\field\IRelationField;
use umi\orm\metadata\field\numeric\BoolField;
use umi\orm\metadata\field\string\TextField;
use umicms\exception\RuntimeException;
use umicms\form\element\Wysiwyg;
use umicms\hmvc\component\admin\TActionController;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\IRecoverableCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\collection\CmsPageCollection;
use umicms\orm\collection\CmsHierarchicPageCollection;
use umicms\orm\metadata\field\relation\BelongsToRelationField;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\IRecoverableObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\project\module\service\model\object\Backup;
use umicms\project\module\users\model\object\RegisteredUser;

/**
 * Контроллер действий над объектом.
 */
class ActionController extends BaseController implements IFormAware
{
    use TActionController;
    use TFormAware;

    /**
     * @var ITranslator $translator транслятор
     */
    protected $translator;

    public function __construct(ITranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Возвращает форму для редактирования объекта коллекции.
     * @throws HttpException
     * @return \ArrayObject
     */
    protected function actionGetEditForm()
    {
        $typeName = $this->getRequiredQueryVar('type');

        return $this->getCollection()->getForm(ICmsCollection::FORM_EDIT, $typeName)->getView();
    }

    /**
     * Возвращает форму для создания объекта коллекции.
     * @throws HttpException
     * @return \ArrayObject
     */
    protected function actionGetCreateForm()
    {
        $typeName = $this->getRequiredQueryVar('type');
        $collection = $this->getCollection();

        return [
            'guid' => $collection->getGUIDField()->generateGUID(),
            'form' => $collection->getForm(ICmsCollection::FORM_CREATE, $typeName)->getView()
        ];
    }

    /**
     * Возвращает форму для фильтрации данных в коллекции.
     * @return \ArrayObject
     */
    protected function actionGetFilter()
    {
        $elements = [];
        $collection = $this->getCollection();
        $ignoredFieldNames = $collection->getIgnoredTableFilterFieldNames();
        foreach ($this->getCollection()->getMetadata()->getFields() as $field) {

            if (
                ($field instanceof IRelationField && !$field instanceof BelongsToRelationField)
                || in_array($field->getName(), $ignoredFieldNames)
            ) {
                continue;
            }

            $fieldName = $field->getName();
            $label = $this->translator->translate($collection->getDictionaryNames(), $fieldName);

            $elements[] = $this->buildFormElement($field, $collection, $fieldName, $fieldName, $label);


            if ($field instanceof BelongsToRelationField) {
                /**
                 * @var ICmsCollection $targetCollection
                 */
                $targetCollection = $field->getTargetCollection();
                $targetIgnoredFieldNames = $targetCollection->getIgnoredTableFilterFieldNames();
                foreach ($targetCollection->getMetadata()->getFields() as $relatedField) {

                    if (
                        $relatedField instanceof IRelationField
                        || in_array($relatedField->getName(), $targetIgnoredFieldNames)
                    ) {
                        continue;
                    }

                    $relatedFieldName = $relatedField->getName();
                    $relatedDataSource = $relatedElementName = $fieldName . '.' . $relatedFieldName;
                    $relatedLabel = $label . ': ' . $this->translator->translate($targetCollection->getDictionaryNames(), $relatedFieldName);

                    $elements[] = $this->buildFormElement(
                        $relatedField, $targetCollection, $relatedElementName, $relatedDataSource, $relatedLabel
                    );
                }

            }
        }

        $form = $this->createForm([]);

        foreach ($elements as $element) {
            $form->add($element);
        }

        return ['defaultFields' => $collection->getDefaultTableFilterFieldNames(), 'form' => $form->getView()];
    }

    /**
     * Формирует элемент формы на основе поля метаданных
     * @param IField $field
     * @param ICmsCollection $collection
     * @param string $elementName имя элемента формы
     * @param string $dataSource имя источника значения
     * @param string $label лейбл
     * @return IFormEntity
     */
    protected function buildFormElement(IField $field, ICmsCollection $collection, $elementName, $dataSource, $label)
    {
        $options = [
            'dataSource' => $dataSource
        ];

        switch(true) {
            case ($field->getName() === ICmsObject::FIELD_TYPE): {
                $type = Select::TYPE_NAME;
                $options['choices'] = [];
                foreach ($collection->getMetadata()->getTypesList() as $typeName) {
                    $options['choices'][$typeName] =
                        $this->translator->translate($collection->getDictionaryNames(), 'type:' . $typeName . ':displayName');
                }
                break;
            }
            case $field instanceof BelongsToRelationField: {
                $type = Select::TYPE_NAME;
                $options['lazy'] = true;
                break;
            }
            case $field instanceof BoolField: {
                $type = Checkbox::TYPE_NAME;
                break;
            }
            case ($field instanceof TextField || $field instanceof Wysiwyg): {
                $type = Textarea::TYPE_NAME;
                break;
            }
            case ($field instanceof DateTimeField): {
                $type = DateTime::TYPE_NAME;
                break;
            }
            default: {
                $type = Text::TYPE_NAME;
            }
        }

        return $this->createFormEntity(
            $elementName,
            [
                'type' => $type,
                'label' => $label,
                'options' => $options
            ]
        );
    }

    /**
     * Изменяет последнюю часть ЧПУ страницы.
     * @throws RuntimeException если невозможно изменить
     * @throws HttpException если пришли неверные данные
     * @return ICmsPage
     */
    protected function changeSlug()
    {
        $data = $this->getIncomingData();
        $object = $this->getEditedObject($data);

        if (isset($data[ICmsPage::FIELD_PAGE_SLUG])) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot change object slug. Slug is required.')
            );
        }

        if (!$object instanceof ICmsPage) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot change object slug. Object should be instance of "{class}".',
                    ['class' => 'umicms\orm\object\ICmsPage']
                )
            );
        }

        $collection = $this->getCollection();

        /**
         * @var CmsHierarchicObject|ICmsPage $object
         */
        if ($collection instanceof CmsHierarchicPageCollection) {
            $collection->changeSlug($object, $data[ICmsPage::FIELD_PAGE_SLUG]);
        } elseif ($collection instanceof CmsPageCollection) {
            $collection->changeSlug($object, $data[ICmsPage::FIELD_PAGE_SLUG]);
        } else {
            throw new RuntimeException(
                $this->translate(
                    'Cannot change object slug. Collection "{collection}" should be either instance of PageHierarchicCollection or instance of PageCollection.',
                    [
                        'collection' => $collection->getName()
                    ]
                )
            );
        }

        return $object;
    }

    /**
     * Изменяет активность объекта.
     * @throws RuntimeException если невозможно выполнить действие
     * @return ICmsObject
     */
    protected function actionActivate()
    {
        $collection = $this->getCollection();
        $object = $this->getEditedObject($this->getIncomingData());

        if (!$collection instanceof IActiveAccessibleCollection || !$object instanceof IActiveAccessibleObject) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot switch object activity. Collection "{collection}" and its objects should be active accessible.',
                    ['collection' => $collection->getName()]
                )
            );
        }

        /**
         * @var IActiveAccessibleObject $object
         */
         $collection->activate($object);

        $this->commit();

        return '';
    }

    /**
     * Изменяет активность объекта.
     * @throws RuntimeException если невозможно выполнить действие
     * @return ICmsObject
     */
    protected function actionDeactivate()
    {
        $collection = $this->getCollection();
        $object = $this->getEditedObject($this->getIncomingData());

        if (!$collection instanceof IActiveAccessibleCollection || !$object instanceof IActiveAccessibleObject) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot switch object activity. Collection "{collection}" and its objects should be active accessible.',
                    ['collection' => $collection->getName()]
                )
            );
        }

        /**
         * @var IActiveAccessibleObject $object
         */
        $collection->deactivate($object);

        $this->commit();

        return '';
    }

    /**
     * Удаляет объект в корзину.
     * @throws RuntimeException если невозможно выполнить действие
     * @return string
     */
    protected function actionTrash()
    {
        $collection = $this->getCollection();
        $object = $this->getEditedObject($this->getIncomingData());

        if (!$collection instanceof IRecyclableCollection || !$object instanceof IRecyclableObject) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot trash object. Collection "{collection}" and its objects should be recyclable.',
                    ['collection' => $collection->getName()]
                )
            );
        }

        /**
         * @var IRecyclableObject $object
         */
        $collection->trash($object);

        $this->commit();

        return '';
    }

    /**
     * Восстанавливает объект из корзины.
     * @throws RuntimeException если невозможно выполнить действие
     * @return string
     */
    protected function actionUntrash()
    {
        $collection = $this->getCollection();
        $object = $this->getEditedObject($this->getIncomingData());

        if (!$collection instanceof IRecyclableCollection || !$object instanceof IRecyclableObject) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot untrash object. Collection "{collection}" and its objects should be recyclable.',
                    ['collection' => $collection->getName()]
                )
            );
        }

        /**
         * @var IRecyclableObject $object
         */
        $collection->trash($object);

        $collection->untrash($object);
        $this->commit();

        return '';
    }

    /**
     * Возвращает список резервных копий.
     * @throws RuntimeException если невозможно выполнить действие
     * @return Backup[]
     */
    protected function actionGetBackupList()
    {
        $collection = $this->getCollection();
        $object = $collection->getById($this->getRequiredQueryVar('id'));

        if (!$collection instanceof IRecoverableCollection || !$object instanceof IRecoverableObject) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot get backup list for object. Collection "{collection}" and its objects should be recoverable.',
                    ['collection' => $collection->getName()]
                )
            );
        }
        /**
         * @var IRecoverableObject $object
         */
        return $collection->getBackupList($object)
            ->with(Backup::FIELD_OWNER, [RegisteredUser::FIELD_DISPLAY_NAME]);
    }

    /**
     * Возвращает резервную копию.
     * @throws RuntimeException если невозможно выполнить действие
     * @return ICmsObject
     */
    protected function actionGetBackup()
    {
        $collection = $this->getCollection();
        $object = $collection->getById($this->getRequiredQueryVar('id'));
        $backupId = $this->getRequiredQueryVar('backupId');

        if (!$collection instanceof IRecoverableCollection || !$object instanceof IRecoverableObject) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot get backup for object. Collection "{collection}" and its objects should be recoverable.',
                    ['collection' => $collection->getName()]
                )
            );
        }
        /**
         * @var IRecoverableObject $object
         */
        return $collection->wakeUpBackup($object, $backupId);
    }

    /**
     * Перемещает объекты.
     * @throws RuntimeException если невозможно выполнить действие
     * @throws HttpException если пришли невалидные данные
     * @return string
     */
    protected function actionMove()
    {
        $collection = $this->getCollection();

        if (!$collection instanceof ISimpleHierarchicCollection) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot move objects. Collection "{collection}" should be hierarchic.',
                    ['collection' => $collection->getName()]
                )
            );
        }

        $data = $this->getIncomingData();

        if (!isset($data['object'])) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot move objects. Objects info is undefined.')
            );
        }

        /**
         * @var CmsHierarchicObject $object
         */
        $object = $this->getEditedObject($data['object']);
        /**
         * @var CmsHierarchicObject $branch
         */
        $branch = isset($data['branch']) ? $this->getEditedObject($data['branch']) : null;
        /**
         * @var CmsHierarchicObject $previousSibling
         */
        $previousSibling = isset($data['sibling']) ? $this->getEditedObject($data['sibling']) : null;

        $collection->move($object, $branch, $previousSibling);

        return '';
    }

    /**
     * Возвращает объект для редактирования.
     * @param array $data данные объекта
     * @throws HttpException если данные невалидные.
     * @return ICmsObject
     */
    protected function getEditedObject(array $data)
    {
        if (!isset($data[ICmsObject::FIELD_IDENTIFY])) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot get object. Object id required.')
            );
        }

        if (!isset($data[ICmsObject::FIELD_VERSION])) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot get object. Object version required.')
            );
        }

        $object = $this->getCollection()->getById($data[ICmsObject::FIELD_IDENTIFY]);
        $object->setVersion($data[ICmsObject::FIELD_VERSION]);

        return $object;
    }

}
