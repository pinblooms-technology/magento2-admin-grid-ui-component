<?php

declare(strict_types=1);

namespace PinBlooms\MasterData\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\App\Action\Context;

class Search extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory
    ) {
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * Execute the action and return JSON response
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        // Create JSON result instance
        $result = $this->jsonFactory->create();

        // Example: Get search query parameter
        $query = $this->getRequest()->getParam('q'); // 'q' is the default query parameter for ui-select

        // Search logic (replace this with your actual data source)
        $data = [
            ['value' => 'type1', 'label' => 'Type 1'],
            ['value' => 'type2', 'label' => 'Type 2'],
            ['value' => 'type3', 'label' => 'Type 3'],
        ];

        // Filter results based on the query (simple example)
        $filteredData = array_filter($data, function ($item) use ($query) {
            return stripos($item['label'], $query) !== false;
        });

        // Return JSON response
        return $result->setData(array_values($filteredData));
    }
}
