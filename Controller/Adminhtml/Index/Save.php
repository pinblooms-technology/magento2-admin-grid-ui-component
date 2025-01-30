<?php

declare(strict_types=1);

namespace PinBlooms\MasterData\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use PinBlooms\MasterData\Model\MasterDataFactory;

class Save extends Action
{
    /**
     * @var MasterDataFactory
     */
    private $masterDataFactory;

    /**
     * Construtor function
     *
     * @param Context $context
     * @param MasterDataFactory $masterDataFactory
     */
    public function __construct(
        Context $context,
        MasterDataFactory $masterDataFactory
    ) {
        parent::__construct($context);
        $this->masterDataFactory = $masterDataFactory;
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getParams();

        if (empty($data)) {
            $this->messageManager->addErrorMessage(__('Invalid data.'));
            return $resultRedirect->setPath('*/*/index');
        }

        try {
            $model = $this->masterDataFactory->create();

            if (!empty($data['entity_id'])) {
                $model->load($data['entity_id']);
            }

            $model->addData($data);
            $model->save();

            $this->messageManager->addSuccessMessage(__('Data has been saved successfully.'));

            return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the data.'));
        }

        return $resultRedirect->setPath('*/*/index');
    }
}
