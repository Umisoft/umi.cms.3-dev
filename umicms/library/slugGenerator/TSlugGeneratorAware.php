<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\slugGenerator;

use umicms\exception\RequiredDependencyException;

/**
 * Трейт для поддержки генерации slug'ов.
 */
trait TSlugGeneratorAware
{
    /**
     * @var ISlugGenerator $traitSlugGenerator
     */
    private $traitSlugGenerator;

    /**
     * @see ISlugGeneratorAware::setSlugGenerator
     */
    public function setSlugGenerator(ISlugGenerator $slugGenerator)
    {
        $this->traitSlugGenerator = $slugGenerator;
    }

    /**
     * Возвращает генератор slug'ов.
     * @throws RequiredDependencyException если генератор не был внедрен
     * @return ISlugGenerator
     */
    protected function getSlugGenerator()
    {
        if (!$this->traitSlugGenerator) {
            throw new RequiredDependencyException(sprintf(
                'Slug generator is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitSlugGenerator;
    }
} 