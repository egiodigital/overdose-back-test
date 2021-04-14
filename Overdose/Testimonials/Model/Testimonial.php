<?php
/**
 * Copyright © 2021 Overdose_Testimonials. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Overdose\Testimonials\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Module
 * @package Overdose\Testimonials\Model
 */
class Testimonial extends AbstractModel
{

    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init(\Overdose\Testimonials\Model\ResourceModel\Testimonial::class);
    }
}
