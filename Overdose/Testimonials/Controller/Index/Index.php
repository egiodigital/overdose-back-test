<?php
/**
 * Copyright © 2021 Overdose_Testimonials. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Overdose\Testimonials\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Index
 * @package Overdose\Testimonials\Controller\Index
 */
class Index extends Action
{
    /**
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
