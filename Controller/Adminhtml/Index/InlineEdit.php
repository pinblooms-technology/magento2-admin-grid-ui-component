<?php

namespace PinBlooms\MasterData\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use PinBlooms\MasterData\Model\MasterData;

class InlineEdit extends Action
{
    /**
     * Undocumented variable
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    /**
     * Undocumented variable
     *
     * @var \PinBlooms\MasterData\Model\MasterData
     */
    protected $masterData;

    /**
     * COnstructor function
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param MasterData $masterData
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        MasterData $masterData
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->masterData = $masterData;
    }

    /**
     * Inline edit function
     *
     * @return void
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];
        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (empty($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $entityId) {
                    $modelData = $this->masterData->load($entityId);
                    try {
                        $modelData->setData(array_merge($modelData->getData(), $postItems[$entityId]));
                        $modelData->save();
                    } catch (\Exception $e) {
                        $messages[] = "[Error:]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
