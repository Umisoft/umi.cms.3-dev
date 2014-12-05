<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\metatag\model;

use umicms\orm\collection\ICmsPageCollection;
use umicms\orm\object\ICmsPage;
use umicms\project\module\metatag\model\generator\Parser;
use umicms\project\module\metatag\model\object\Template;

/**
 * Class Generator
 * @package umicms\project\module\metatag\model
 */
class MetaTagModule
{

    /**
     * @param Template $template
     */
    public function generateMetaTagsByTemplate(Template $template)
    {
        $parser = new Parser();

        $d = $parser->parse($template->getDescriptionTemplate());
        $k = $parser->parse($template->getKeywordsTemplate());
        $t = $parser->parse($template->getTitleTemplate());

        $objects = $this->getCmsPageObjects($template->getIncludeCollections());

        foreach ($objects as $object) {
            $object->metaDescription = $parser->randomize($d);
            $object->metaKeywords = $parser->randomize($k);
            $object->metaTitle = $parser->randomize($t);
        }
    }

    /**
     * @param ICmsPageCollection[] $cmsPageCollections
     * @return ICmsPage[]
     */
    private function getCmsPageObjects(array $cmsPageCollections)
    {
        $objectArray = [];
        foreach ($cmsPageCollections as $collection) {
            $objectArray = array_merge($collection->select()->getResult()->fetchAll(), $objectArray);
        }
        return $objectArray;
    }

} 