<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\vote\model\object;

use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;


class Vote extends CmsObject implements ICmsPage
{
	use TCmsPage;

    const FIELD_ANSWERS = 'answers';
    const FIELD_MULTIPLE_CHOICE = 'multiple_choice';
}
