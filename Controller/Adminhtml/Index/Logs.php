<?php

namespace PinBlooms\MasterData\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class Logs extends \Magento\Backend\App\Action
{
    /**
     * ResultPF
     * @var PageFactory
     */
    private $resultPageFactory;
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    private $fileFactory;
    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    private $_directory;

    /**
     * Logs constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Framework\Filesystem\DirectoryList $directory
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Filesystem\DirectoryList $directory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_directory = $directory;
        $this->fileFactory = $fileFactory;
    }

    /**
     * Execute
     */
    public function execute()
    {
        try {
            $filePath = $this->_directory->getPath('var') . '/log/pinblooms_masterdata.log';
            $downloadName = 'pinblooms_masterdata.log';
            $content['type'] = 'filename';
            $content['value'] = $filePath;
            $content['rm'] = 0; // If you will set here 1 then, it will remove file from location.
            return $this->fileFactory->create($downloadName, $content, DirectoryList::VAR_DIR);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Exception : ' . $e->getMessage()));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/index');
            return $resultRedirect;
        }
    }

    /**
     * IsALLowed
     *
     * @return boolean
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('PinBlooms_MasterData::masterdata');
    }
}
