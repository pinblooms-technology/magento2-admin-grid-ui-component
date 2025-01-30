<?php

namespace PinBlooms\MasterData\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;

class Truncate extends \Magento\Backend\App\Action
{
    /**
     * @var \PinBlooms\MasterData\Model\ResourceModel\MasterData\CollectionFactory
     */
    public $masterdataCollection;

    /**
     * Truncate function
     *
     * @param Context $context
     * @param \PinBlooms\MasterData\Model\ResourceModel\MasterData\CollectionFactory $masterdataCollection
     */
    public function __construct(
        Context $context,
        \PinBlooms\MasterData\Model\ResourceModel\MasterData\CollectionFactory $masterdataCollection
    ) {
        parent::__construct($context);
        $this->masterdataCollection = $masterdataCollection;
    }

    /**
     * Execute
     *
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Exception
     */
    public function execute()
    {
        $masterDataTruncated = $this->truncateTable($this->masterdataCollection);

        if (!$masterDataTruncated) {
            $this->messageManager->addNoticeMessage(__('No Records Found in Either Table!'));
        } else {
            $this->messageManager->addSuccessMessage(
                __('Master Data Records Truncated Successfully!')
            );
        }
        return $this->_redirect('*/*/index');
    }

    /**
     * Truncate Table function
     *
     * @param object $collectionFactory
     * @return void
     */
    public function truncateTable($collectionFactory)
    {
        try {
            $collection = $collectionFactory->create();
            if (!empty($collection->getAllIds())) {
                $connection = $collection->getConnection();
                $tableName = $collection->getMainTable();
                $connection->truncateTable($tableName);
                return true;
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: %1', $e->getMessage()));
        }
        return false;
    }

    /**
     * Public function _isAllowed
     *
     * @return boolean
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('PinBlooms_MasterData::truncate');
    }
}
