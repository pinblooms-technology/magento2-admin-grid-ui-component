<?php

namespace PinBlooms\MasterData\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Status
 * PinBlooms\MasterData\Ui\Component\Listing\Columns
 */
class Status extends Column
{
    /**
     * @var UrlInterface
     */
    public $urlBuilder;

    /**
     * Json Parser
     *
     * @var \Magento\Framework\Json\Helper\Data
     */
    public $json;

    /**
     * Product Model
     *
     * @var \Magento\Catalog\Model\ProductFactory
     */
    public $product;

    /**
     * Status constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        $components = [],
        $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[$fieldName])) {
                    if ($item[$fieldName] == '1') {
                        $item[$fieldName] = __('Active');
                    } elseif ($item[$fieldName] == '0') {
                        $item[$fieldName] = __('Inactive');
                    } else {
                        $item[$fieldName] = __($item[$fieldName]);
                    }
                }
            }
        }
        return $dataSource;
    }
}
