<?php

namespace PinBlooms\MasterData\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class Add extends Action
{
    const ADMIN_RESOURCE = 'Numismatic_MasterData::masterdata_add';

    /**
     * Execute function
     *
     * @return void
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/edit');
        return $resultRedirect;
    }

    /**
     * Allowed function
     *
     * @return void
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }
}
