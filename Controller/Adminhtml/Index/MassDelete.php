<?php

namespace PinBlooms\MasterData\Controller\Adminhtml\Index;

/**
 * Class MassDelete
 * PinBlooms\MasterData\Controller\Adminhtml\Index
 */
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $_filter;

    /**
     * @var \PinBlooms\MasterData\Model\ResourceModel\MasterData\CollectionFactory
     */
    protected $_collectionFactory;
    /**
     * @var \Magento\UrlRewrite\Model\ResourceModel\UrlRewrite
     */
    protected $urlRewriteResource;
    /**
     * @var \Magento\UrlRewrite\Model\UrlRewriteFactory
     */
    protected $urlRewriteFactory;

    /**
     * MassDelete constructor.
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param MasterData\CollectionFactory $collectionFactory
     * @param \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory
     * @param \Magento\UrlRewrite\Model\ResourceModel\UrlRewrite $urlRewriteResource
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Ui\Component\MassAction\Filter $filter,
        \PinBlooms\MasterData\Model\ResourceModel\MasterData\CollectionFactory
        $collectionFactory,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory,
        \Magento\UrlRewrite\Model\ResourceModel\UrlRewrite $urlRewriteResource,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->_filter            = $filter;
        $this->_collectionFactory = $collectionFactory;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->urlRewriteResource = $urlRewriteResource;
        parent::__construct($context);
    }

    /**
     * Execute
     */
    public function execute()
    {
        try {
            $logCollection = $this->_filter->getCollection($this->_collectionFactory->create());
            $count = 0;
            foreach ($logCollection as $item) {
                // Deleting UrlRewrite Data By Id
                /*$urlReWriteId = $item->getUrlRewriteId();
                $urlRewriteModel = $this->urlRewriteFactory->create();
                $this->urlRewriteResource->load($urlRewriteModel, $urlReWriteId, 'url_rewrite_id');
                if (!empty($urlRewriteModel->getData())) {
                    $this->urlRewriteResource->delete($urlRewriteModel);
                }*/
                // End
                $item->delete();
                $count++;
            }
            $this->messageManager->addSuccessMessage(__($count . ' Entries Deleted Successfully.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index');
    }
}
