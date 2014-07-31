<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\view;

use umi\hmvc\view\IView;
use umi\spl\container\TPropertyAccess;
use umicms\serialization\ISerializerConfigurator;
use umicms\serialization\TSerializerConfigurator;

/**
 * Содержимое результата работы виджета или контроллера, не требующий шаблонизации, но может содержать в себе переменные.
 */
class CmsPlainView extends \ArrayIterator implements IView, ISerializerConfigurator
{
    use TSerializerConfigurator;
    use TPropertyAccess;

    /**
     * @var string $plainContent контент
     */
    protected $plainContent;

    /**
     * Конструктор.
     * @param array $plainContent контент
     */
    public function __construct($plainContent)
    {
        parent::__construct();

        $this->plainContent = $plainContent;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->plainContent;
    }


    /**
     * {@inheritdoc}
     */
    public function get($attribute)
    {
        return $this->offsetGet($attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function set($attribute, $value)
    {
        $this->offsetSet($attribute, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function has($attribute)
    {
        return $this->offsetExists($attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function del($attribute)
    {
        $this->offsetUnset($attribute);

        return $this;
    }

}
 