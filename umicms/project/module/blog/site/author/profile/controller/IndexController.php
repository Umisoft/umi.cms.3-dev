<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\author\profile\controller;

use umi\form\IForm;
use umi\orm\metadata\IObjectType;
use umicms\hmvc\component\site\BaseSitePageController;
use umicms\hmvc\component\site\TFormController;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogAuthor;

/**
 * Контроллер редактирования профиля автора блога.
 */
class IndexController extends BaseSitePageController
{
    use TFormController;

    /**
     * @var BlogModule $api API модуля "Блоги"
     */
    protected $api;
    /**
     * @var bool $success флаг указывающий на успешное сохранение изменений
     */
    private $success = false;

    /**
     * Конструктор.
     * @param BlogModule $blogModule API модуля "Блоги"
     */
    public function __construct(BlogModule $blogModule)
    {
        $this->api = $blogModule;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTemplateName()
    {
        return 'editProfile';
    }

    /**
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        $blogAuthor = $this->api->getCurrentAuthor();

        return $this->api->author()->getForm(
            BlogAuthor::FORM_EDIT_PROFILE,
            IObjectType::BASE,
            $blogAuthor
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->commit();
        $this->success = true;
    }

    protected function buildResponseContent()
    {
        return [
            'success' => $this->success,
            'page' => $this->getCurrentPage()
        ];
    }
}
 