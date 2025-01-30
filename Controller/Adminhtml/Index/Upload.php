<?php

namespace PinBlooms\MasterData\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;

class Upload extends \Magento\Backend\App\Action
{
    /**
     * @var \PinBlooms\MasterData\Logger\Logger
     */
    public $logger;
    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    public $fileIo;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    public $dateTime;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;
    /**
     * @var \Magento\Backend\Model\Session
     */
    public $session;
    /**
     * @var \PinBlooms\MasterData\Helper\Data
     */
    public $helperData;

    /**
     * Upload constructor.
     * @param Context $context
     * @param \Magento\Framework\Session\SessionManagerInterface $session
     * @param \PinBlooms\MasterData\Logger\Logger $logger
     * @param \Magento\Framework\Filesystem\Io\File $fileIo
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \PinBlooms\MasterData\Helper\Data $helperData
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \PinBlooms\MasterData\Logger\Logger $logger,
        \Magento\Framework\Filesystem\Io\File $fileIo,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \PinBlooms\MasterData\Helper\Data $helperData
    ) {
        parent::__construct($context);
        $this->logger = $logger;
        $this->session =  $session;
        $this->fileIo = $fileIo;
        $this->dateTime = $dateTime;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->helperData = $helperData;
    }
    /**
     * Execute
     */
    public function execute()
    {
        $batchId = $this->getRequest()->getParam('batchid');
        $params = $this->getRequest()->getParams();
        if (isset($batchId)) {
            $csvFilePath = $this->session->getCsvFilePath();
            $response = $this->helperData->updateRecord($csvFilePath, $batchId + 1);
            $resultJson = $this->resultJsonFactory->create();
            if ($response['success'] && isset($response['message'])) {
                return $resultJson->setData([
                    'success' => "<span> " . $response['message'] . "</span>"
                ]);
            } else {
                return $resultJson->setData([
                    'success' => "<span> " . $response['message'] . "</span>"
                ]);
            }
        }
        try {
            if (isset($params['custom_csv']) && isset($params['custom_csv'][0])) {
                $csvFile = $params['custom_csv'][0]['path'] . '/' . $params['custom_csv'][0]['file'];
                $response = $this->helperData->getCsvDataCount($csvFile);
                $this->session->setIdsCount($response);
                $this->session->setCsvFilePath($csvFile);
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('PinBlooms_MasterData::masterdata');
                $resultPage->getConfig()->getTitle()->prepend(__('Upload/Update Csv Data'));
                return $resultPage;
            } else {
                $this->messageManager->addWarningMessage('No Csv Files Found After Upload.');
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('*/*/import');
                return $resultRedirect;
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('Exception : ' . $e->getMessage());
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/import');
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
        return $this->_authorization->isAllowed(
            'PinBlooms_MasterData::masterdata'
        );
    }
}
