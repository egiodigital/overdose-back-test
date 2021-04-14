<?php
/**
 * Copyright © 2021 Overdose_Testimonials. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Overdose\Testimonials\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Testimonial
 * @package Overdose\Testimonials\Model\ResourceModel
 */
class Testimonial extends AbstractDb
{
    /**
     * initialise table and primary id
     */
    protected function _construct()
    {
        $this->_init('overdose_testimonials', 'testimonial_id');
    }
}
