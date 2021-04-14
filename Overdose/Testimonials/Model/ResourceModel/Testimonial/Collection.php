<?php
/**
 * Copyright © 2021 Overdose_Testimonials. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Overdose\Testimonials\Model\ResourceModel\Testimonial;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Overdose\Testimonials\Model\Testimonial as ModelTestimonial;
use Overdose\Testimonials\Model\ResourceModel\Testimonial;

/**
 * Class Collection
 * @package Overdose\Testimonials\Model\ResourceModel\Testimonial
 */
class Collection extends AbstractCollection
{

    /**
     * initialise model and resource model
     */
    protected function _construct()
    {
        $this->_init(
            ModelTestimonial::class,
            Testimonial::class
        );
    }

    /**
     * @return string
     * get field name
     */
    public function getIdFieldName()
    {
        return 'testimonial_id';
    }
}
