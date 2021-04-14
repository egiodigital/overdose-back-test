<?php
/**
 * Copyright © 2021 Overdose_Testimonials. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Overdose\Testimonials\Controller\Add;

/*
 * Class Index
 * @package Overdose\Testimonials\Controller\Add
 */

use Exception;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Overdose\Testimonials\Helper\Data as HelperData;
use Overdose\Testimonials\Model\Testimonial;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\PageCache\Version;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;

class Index extends Action
{
    /**
     * @var HelperData
     */
    protected $helper;

    /**
     * @var Testimonial
     */
    protected $testimonialLoader;

    /**
     * @var TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * @var Pool
     */
    protected $cacheFrontendPool;

    /**
     * @var Context
     */
    private $context;

    /**
     * Index constructor.
     * @param Context $context
     * @param HelperData $helper
     * @param Testimonial $testimonialLoader
     * @param TypeListInterface $cacheTypeList,
     * @param Pool $cacheFrontendPool
     *
     */
    public function __construct(
        Context $context,
        HelperData $helper,
        Testimonial $testimonialLoader,
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool
    ) {
        $this->helper = $helper;
        $this->testimonialLoader = $testimonialLoader;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
        parent::__construct($context);
        $this->context = $context;
    }

    /**
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $item = $this->testimonialLoader;

        try {
            $this->validateData($data);
            $imageRequest = $this->getRequest()->getFiles('image');
            $data = $this->helper->imageUpload($data, $imageRequest);
            if (isset($data['error_size']) && $data['error_size'] == true) {
                $this->messageManager->addErrorMessage(
                    __('You have exceeded the max file size.')
                );
            } else {
                $item->setData($data);
                $item->save();
                $this->flushCache();
                $this->messageManager->addSuccessMessage(
                    __('Thanks for your valuable time.')
                );
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(
                __('An error occurred while processing your data. Please try again.')
            );
        }
        return $this->resultRedirectFactory->create()->setPath('testimonials');
    }

    /**
     * @param $data
     * @throws LocalizedException
     */
    private function validateData($data)
    {
        if (isset($data['title']) && trim($data['title']) === '') {
            throw new LocalizedException(__('Enter the title and try again.'));
        }
        if (isset($data['message']) && trim($data['message']) === '') {
            throw new LocalizedException(__('Enter the message and try again.'));
        }
    }

    /**
     * Clear cache
     */
    public function flushCache()
    {
        $this->cacheTypeList->cleanType('block_html');
        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}
