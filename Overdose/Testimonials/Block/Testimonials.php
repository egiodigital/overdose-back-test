<?php
/**
 * Copyright © 2021 Overdose_Testimonials. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Overdose\Testimonials\Block;

use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;
use Overdose\Testimonials\Model\TestimonialFactory;
use Magento\Framework\View\Element\Template;
use Overdose\Testimonials\Helper\Data;

/**
 * Class Testimonials
 * @package Overdose\Testimonials\Block
 */
class Testimonials extends Template
{

    /**
     * @var TestimonialFactory
     */
    protected $testimonialFactory;

    /**
     * @var AdapterFactory
     */
    protected $_imageFactory;

    /**
     * @var Data
     */
    private $helper;

    /**
     * Testimonials constructor.
     * @param Context $context
     * @param TestimonialFactory $testimonialFactory
     * @param AdapterFactory $imageFactory ,
     * @param Data $helper ,
     * @param array $data
     */
    public function __construct(
        Context $context,
        TestimonialFactory $testimonialFactory,
        AdapterFactory $imageFactory,
        Data $helper,
        array $data = []
    )
    {
        $this->testimonialFactory = $testimonialFactory;
        $this->helper = $helper;
        $this->_imageFactory = $imageFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getTestimonials()
    {
        //get values of current page
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        //get values of current limit
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 30;

        $collection = $this->testimonialFactory->create()->getCollection();
        $collection->addFieldToFilter('status', ['eq' => 1]);
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        return $collection;
    }

    /**
     * @return $this|Testimonials
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getTestimonials()) {
            $this->getTestimonials()->load();
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('testimonials/add/index', []);
    }

    /**
     * @param $image
     * @return string
     * @throws
     */
    public function getAvatar($image)
    {
        $avatar = '';
        if ($image) {
            $avatar = $this->_storeManager
                    ->getStore()
                    ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . Data::MEDIA_PATH . '/' . $image;
        }
        return $avatar;
    }
}
