<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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