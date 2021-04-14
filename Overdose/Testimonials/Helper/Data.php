<?php
/**
 * Copyright © 2021 Overdose_Testimonials. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Overdose\Testimonials\Helper;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Customer\Model\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context as HelperContext;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Filesystem\Directory\Read;
use Magento\Framework\Image\Adapter\AdapterInterface;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    /**
     * @var string
     */
    const MEDIA_PATH = 'overdose/testimonials/images';

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var UploaderFactory
     */
    protected $_uploaderFactory;

    /**
     * @var AdapterFactory
     */
    protected $_adapterFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var HttpContext
     */
    protected $httpContext;

    /**
     * @param HelperContext $context
     * @param StoreManagerInterface $storeManager
     * @param UploaderFactory $uploaderFactory
     * @param AdapterFactory $adapterFactory
     * @param ManagerInterface $messageManager
     * @param ObjectManagerInterface $objectManager
     * @param HttpContext $httpContext
     */
    public function __construct(
        HelperContext $context,
        StoreManagerInterface $storeManager,
        UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        ManagerInterface $messageManager,
        ObjectManagerInterface $objectManager,
        HttpContext $httpContext
    ) {
        $this->_storeManager = $storeManager;
        $this->_uploaderFactory = $uploaderFactory;
        $this->_adapterFactory = $adapterFactory;
        $this->messageManager = $messageManager;
        $this->_objectManager = $objectManager;
        $this->httpContext = $httpContext;
        parent::__construct($context);
    }

    /**
     * @param array $data
     * @param array $imageRequest
     * @return array
     */
    public function imageUpload($data, $imageRequest)
    {
        if ($imageRequest) {
            if (isset($imageRequest['name'])) {
                $fileName = $imageRequest['name'];
            } else {
                $fileName = '';
            }
        } else {
            $fileName = '';
        }

        if ($imageRequest && strlen($fileName)) {
            try {
                if ($imageRequest['size'] && $imageRequest['size'] <= 1000000) { // Limit is set to 1 MB
                    $uploader = $this->_uploaderFactory->create(['fileId' => 'image']);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);

                    /** @var AdapterInterface $imageAdapter */
                    $imageAdapter = $this->_adapterFactory->create();

                    $uploader->addValidateCallback('image', $imageAdapter, 'validateUploadFile');

                    /** @var Read $mediaDirectory */
                    $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                        ->getDirectoryRead(DirectoryList::MEDIA);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath(self::MEDIA_PATH)
                    );
                    $data['image'] = $result['file'];
                } else {
                    $data['error_size'] = true;
                }
            } catch (Exception $e) {
                if ($e->getCode() == 0) {
                    $this->messageManager->addError($e->getMessage());
                }
            }
        } else {
            if (isset($data['image']) && isset($data['image']['value'])) {
                if (isset($data['image']['delete'])) {
                    $data['image'] = null;
                    $data['delete_image'] = true;
                } elseif (isset($data['image']['value'])) {
                    $data['image'] = str_replace(self::MEDIA_PATH, '', $data['image']['value']);
                } else {
                    $data['image'] = null;
                }
            }
        }
        return $data;
    }
}
