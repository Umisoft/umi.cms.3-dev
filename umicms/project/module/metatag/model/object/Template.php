<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\metatag\model\object;

use umicms\orm\collection\ICmsPageCollection;
use umicms\orm\object\CmsObject;

/**
 * Class Template
 * @package umicms\project\module\metatag\model\object
 */
class Template extends CmsObject
{
    /** @var  string */
    private $name;
    /** @var  string */
    private $titleTemplate;
    /** @var  string */
    private $descriptionTemplate;
    /** @var  string */
    private $keywordsTemplate;
    /** @var  ICmsPageCollection[] */
    private $includeCollections;

    /**
     * @return string
     */
    public function getDescriptionTemplate()
    {
        return $this->descriptionTemplate;
    }

    /**
     * @return string
     */
    public function getKeywordsTemplate()
    {
        return $this->keywordsTemplate;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTitleTemplate()
    {
        return $this->titleTemplate;
    }

    /**
     * @return \umicms\orm\collection\ICmsPageCollection[]
     */
    public function getIncludeCollections()
    {
        return $this->includeCollections;
    }


}