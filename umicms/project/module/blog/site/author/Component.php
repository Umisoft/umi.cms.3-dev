<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\author;

use umicms\exception\RuntimeException;
use umicms\orm\object\ICmsPage;
use umicms\project\module\blog\api\object\BlogAuthor;
use umicms\project\site\component\SiteComponent;

/**
 * Компонент "Авторы".
 */
class Component extends SiteComponent
{
    /**
     * {@inheritdoc}
     */
    public function getPageUri(ICmsPage $page) {

        if ($page instanceof BlogAuthor) {
            return $this->getRouter()->assemble('author', ['slug' => $page->slug]);
        }

        throw new RuntimeException(
            $this->translate(
                'Component "{path}" does not support URI generation for page "{guid}" from collection "{collection}".',
                [
                    'path' => $this->getPath(),
                    'guid' => $page->getGUID(),
                    'collection' => $page->getCollection()->getName()
                ]
            )
        );
    }
}
 