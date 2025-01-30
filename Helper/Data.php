<?php

namespace PinBlooms\MasterData\Helper;

use PinBlooms\MasterData\Model\ValueMappingFactory;
use PinBlooms\MasterData\Model\ResourceModel\ValueMapping as ValueMappingResource;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var ValueMappingFactory
     */
    protected $valueMappingFactory;

    /**
     * @var ValueMappingResource
     */
    protected $valueMappingResource;

    /**
     * @var \Magento\Framework\File\Csv
     */
    public $csv;
    /**
     * @var \PinBlooms\MasterData\Logger\Logger
     */
    public $logger;
    /**
     * @var \PinBlooms\MasterData\Model\ResourceModel\MasterData\CollectionFactory
     */
    public $masterDataCollection;
    /**
     * @var \PinBlooms\MasterData\Model\ResourceModel\MasterData
     */
    public $masterDataResource;
    /**
     * @var \PinBlooms\MasterData\Model\MasterDataFactory
     */
    public $masterDataFactory;

    /**
     * @var \Magento\UrlRewrite\Model\ResourceModel\UrlRewrite
     */
    public $urlRewriteResource;

    /**
     * @var Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var \PinBlooms\MasterData\Model\MasterDataProductTypeFactory
     */
    private $masterDataProductTypeFactory;

    /**
     * @var \PinBlooms\MasterData\Model\ResourceModel\MasterDataProductType
     */
    private $masterDataProductTypeResource;

    /**
     * @var \PinBlooms\MasterData\Model\ResourceModel\MasterDataIssueType
     */
    private $masterDataIssueTypeResource;

    /**
     * @var \PinBlooms\MasterData\Model\MasterDataIssueTypeFactory
     */
    private $masterDataIssueTypeFactory;

    /**
     * Data function
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\File\Csv $csv
     * @param \PinBlooms\MasterData\Model\MasterDataFactory $masterDataFactory
     * @param \PinBlooms\MasterData\Model\ResourceModel\MasterData $masterDataResource
     * @param \PinBlooms\MasterData\Model\ResourceModel\MasterData\CollectionFactory $masterDataCollection
     * @param \PinBlooms\MasterData\Logger\Logger $logger
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     * @param ValueMappingFactory $valueMappingFactory
     * @param ValueMappingResource $valueMappingResource
     * @param \PinBlooms\MasterData\Model\MasterDataProductTypeFactory $masterDataProductTypeFactory
     * @param \PinBlooms\MasterData\Model\ResourceModel\MasterDataProductType $masterDataProductTypeResource
     * @param \PinBlooms\MasterData\Model\ResourceModel\MasterDataIssueType $masterDataIssueTypeResource
     * @param \PinBlooms\MasterData\Model\MasterDataIssueTypeFactory $masterDataIssueTypeFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\File\Csv $csv,
        \PinBlooms\MasterData\Model\MasterDataFactory $masterDataFactory,
        \PinBlooms\MasterData\Model\ResourceModel\MasterData $masterDataResource,
        \PinBlooms\MasterData\Model\ResourceModel\MasterData\CollectionFactory
        $masterDataCollection,
        \PinBlooms\MasterData\Logger\Logger $logger,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        ValueMappingFactory $valueMappingFactory,
        ValueMappingResource $valueMappingResource,
        \PinBlooms\MasterData\Model\MasterDataProductTypeFactory $masterDataProductTypeFactory,
        \PinBlooms\MasterData\Model\ResourceModel\MasterDataProductType $masterDataProductTypeResource,
        \PinBlooms\MasterData\Model\ResourceModel\MasterDataIssueType $masterDataIssueTypeResource,
        \PinBlooms\MasterData\Model\MasterDataIssueTypeFactory $masterDataIssueTypeFactory
    ) {
        parent::__construct($context);
        $this->csv = $csv;
        $this->masterDataFactory = $masterDataFactory;
        $this->masterDataResource = $masterDataResource;
        $this->masterDataCollection = $masterDataCollection;
        $this->logger = $logger;
        $this->resourceConnection = $resourceConnection;
        $this->valueMappingFactory = $valueMappingFactory;
        $this->valueMappingResource = $valueMappingResource;
        $this->masterDataProductTypeFactory = $masterDataProductTypeFactory;
        $this->masterDataProductTypeResource = $masterDataProductTypeResource;
        $this->masterDataIssueTypeResource = $masterDataIssueTypeResource;
        $this->masterDataIssueTypeFactory = $masterDataIssueTypeFactory;
    }

    /**
     * GetCsvDataCount
     *
     * @param string $csvFilePath
     *
     * @return int|string|null
     */
    public function getCsvDataCount($csvFilePath = '')
    {
        try {
            $data = $this->csv->getData($csvFilePath);
            if (!empty($data) && isset($data[0])) {
                unset($data[0]); // ignore first header column and read data
                end($data); // move the internal pointer to the end of the array
                $key = key($data);  // fetches the key of the element pointed to by the internal pointer
            }
            return (isset($key)) ? $key : 0;
        } catch (\Exception $e) {
            $this->logger->info(
                "PinBlooms_MasterData",
                ['Message' => $e->getMessage(), 'Path' => __METHOD__]
            );
            return 0;
        }
    }

    /**
     * ImportIssueTypeData
     *
     * @param string $csvFilePath
     * @param int $key
     *
     * @return array
     */
    public function importIssueTypeData($csvFilePath = '', $key = 1)
    {
        try {
            $csvData = $this->csv->getData($csvFilePath);
            if (!empty($csvData) && isset($csvData[0]) && isset($csvData[$key])) {
                $headers = $csvData[0]; // Get headers of CSV
                $issueTypeKey = $this->getKeyByHeader('m_issue_type', $headers); // Column name for issue type

                if ($issueTypeKey === false) {
                    return ['success' => false, 'message' => '"m_issue_type" column not found in CSV'];
                }

                $issueTypeValue = trim($csvData[$key][$issueTypeKey]);

                if (empty($issueTypeValue)) {
                    return ['success' => false, 'message' => 'No Issue Type value found on key: ' . $key];
                }

                // Load the resource and factory for the issue type model
                $issueTypeModel = $this->masterDataIssueTypeFactory->create();
                $this->masterDataIssueTypeResource->load(
                    $issueTypeModel,
                    $issueTypeValue,
                    'issue_type_value'
                );

                if (!empty($issueTypeModel->getData())) {
                    // Update existing issue type if it exists
                    $issueTypeModel->setIssueTypeValue($issueTypeValue);
                    $this->masterDataIssueTypeResource->save($issueTypeModel);
                    $message = 'Updated Successfully For Issue Type: ' . $issueTypeValue;
                } else {
                    // Create a new issue type entry
                    $issueTypeModel->setData([
                        'issue_type_value' => $issueTypeValue,
                    ]);
                    $this->masterDataIssueTypeResource->save($issueTypeModel);
                    $message = 'Uploaded Successfully For Issue Type: ' . $issueTypeValue;
                }

                return ['success' => true, 'message' => $message];
            } else {
                return ['success' => false, 'message' => 'No Header OR No Data Found on key: ' . $key];
            }
        } catch (\Exception $e) {
            $this->logger->info(
                "PinBlooms_MasterData",
                ['Message' => $e->getMessage(), 'Path' => __METHOD__]
            );
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    /**
     * ImportProductTypeData
     *
     * @param string $csvFilePath
     * @param int $key
     *
     * @return array
     */
    public function importProductTypeData($csvFilePath = '', $key = 1)
    {
        try {
            $csvData = $this->csv->getData($csvFilePath);
            if (!empty($csvData) && isset($csvData[0]) && isset($csvData[$key])) {
                $headers = $csvData[0]; // Get headers of CSV
                $productTypeKey = $this->getKeyByHeader('m_product_type', $headers); // Column name for product type

                if ($productTypeKey === false) {
                    return ['success' => false, 'message' => '"m_product_type" column not found in CSV'];
                }

                $productTypeValue = trim($csvData[$key][$productTypeKey]);

                if (empty($productTypeValue)) {
                    return ['success' => false, 'message' => 'No Product Type value found on key: ' . $key];
                }

                // Load the resource and factory for the product type model
                $productTypeModel = $this->masterDataProductTypeFactory->create();
                $this->masterDataProductTypeResource->load(
                    $productTypeModel,
                    $productTypeValue,
                    'product_type_value'
                );

                if (!empty($productTypeModel->getData())) {
                    // Update existing product type if it exists
                    $productTypeModel->setProductTypeValue($productTypeValue);
                    $this->masterDataProductTypeResource->save($productTypeModel);
                    $message = 'Updated Successfully For Product Type: ' . $productTypeValue;
                } else {
                    // Create a new product type entry
                    $productTypeModel->setData([
                        'product_type_value' => $productTypeValue,
                    ]);
                    $this->masterDataProductTypeResource->save($productTypeModel);
                    $message = 'Uploaded Successfully For Product Type: ' . $productTypeValue;
                }

                return ['success' => true, 'message' => $message];
            } else {
                return ['success' => false, 'message' => 'No Header OR No Data Found on key: ' . $key];
            }
        } catch (\Exception $e) {
            $this->logger->info(
                "PinBlooms_MasterData",
                ['Message' => $e->getMessage(), 'Path' => __METHOD__]
            );
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * UpdateRecord
     *
     * @param string $csvFilePath
     * @param int $key
     *
     * @return array
     */
    public function updateRecord($csvFilePath = '', $key = 1)
    {
        try {
            $csvData = $this->csv->getData($csvFilePath);
            if (!empty($csvData) && isset($csvData[0]) && isset($csvData[$key])) {
                $headers = $csvData[0]; // Get headers of Csv
                $m_sku_key = $this->getKeyByHeader(
                    \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_SKU,
                    $headers
                );
                $mSkuValue = trim($csvData[$key][$m_sku_key]);
                if (isset($mSkuValue) && !empty($mSkuValue)) {
                    // Simplified logic to handle SKU as a single value
                    $model = $this->masterDataFactory->create();
                    $this->masterDataResource->load(
                        $model,
                        $mSkuValue,
                        \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_SKU
                    );
                    if (!empty($model->getData())) {
                        $returnModel = $this->setDataInModel($headers, $model, $csvData[$key], false);
                        $this->masterDataResource->save($returnModel);
                        $data = [
                            'success' => true,
                            'message' => 'Updated Successfully For Master SKU : ' . $mSkuValue
                        ];
                    } else {
                        $model = $this->masterDataFactory->create();
                        $returnModel = $this->setDataInModel($headers, $model, $csvData[$key], true);
                        $this->masterDataResource->save($returnModel);
                        $data = [
                            'success' => true,
                            'message' => 'Uploaded Successfully For Master SKU : ' . $mSkuValue
                        ];
                    }
                } else {
                    $data = ['success' => false, 'message' => 'No Header OR No Data Found On key : ' . $key];
                }
            } else {
                $data = ['success' => false, 'message' => 'No Header OR No Data Found On key : ' . $key];
            }
        } catch (\Exception $e) {
            $this->logger->info(
                "PinBlooms_MasterData",
                ['Message' => $e->getMessage(), 'Path' => __METHOD__]
            );
            $data = ['success' => false, 'message' => $e->getMessage()];
        }
        return $data;
    }



    /**
     * Get parent IDs for a given record.
     *
     * @param array $data
     * @param string $childValue
     * @param string $childKey
     * @param string $parentKey
     * @param array $updateParentData
     * @return array
     */
    private function getParentKeyId($data, $childValue, $childKey, $parentKey, $updateParentData)
    {
        $parentData = [];
        foreach ($data as $item) {
            if (isset($item[$childKey]) && $item[$childKey] === $childValue) {
                foreach ($updateParentData as $childItem) {
                    if (!empty($childItem['name']) && !empty($item[$parentKey]) && $childItem['name'] === $item[$parentKey]) {
                        $parentData[] = $childItem['entity_id'];
                    }
                }
            }
        }
        return $parentData;
    }

    /**
     * Is Duplicate Entry function
     *
     * @param string $name
     * @param string $headerLabel
     * @return boolean
     */
    private function isDuplicateEntry($name, $headerLabel)
    {
        $collection = $this->valueMappingFactory->create()->getCollection();
        $collection->addFieldToFilter('name', $name)
            ->addFieldToFilter('header_label', $headerLabel);
        return $collection->getSize() > 0; // Returns true if a duplicate exists
    }

    /**
     * GetKeyByHeader
     *
     * @param string $keyName
     * @param array $headers
     *
     * @return false|int|string
     */
    private function getKeyByHeader($keyName, $headers)
    {
        return array_search($keyName, $headers);
    }

    /**
     * SetDataInModel
     *
     * @param array $headers
     * @param Object $model
     * @param array $data
     * @param bool $saveQR
     * @param bool $generatedQr
     * @param string $generatedQrValue
     *
     * @return mixed
     */
    private function setDataInModel(
        $headers,
        $model,
        $data,
        $saveQR = false,
        $generatedQr = false,
        $generatedQrValue = ''
    ) {
        if ($saveQR) {
            if ($generatedQr) {
                $model->setData(
                    \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_SKU,
                    trim($generatedQrValue)
                );
            } else {
                $model->setData(
                    \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_SKU,
                    trim($data[$this->getKeyByHeader(
                        \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_SKU,
                        $headers
                    )])
                );
            }
        }
        $model->setData(
            \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_PRODUCT_TYPE,
            $data[$this->getKeyByHeader(
                \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_PRODUCT_TYPE,
                $headers
            )]
        );
        $model->setData(
            \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_CATEGORY,
            $data[$this->getKeyByHeader(
                \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_CATEGORY,
                $headers
            )]
        );
        $model->setData(
            \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_ISSUE_TYPE,
            $data[$this->getKeyByHeader(
                \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_ISSUE_TYPE,
                $headers
            )]
        );
        $model->setData(
            \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_NAME,
            $data[$this->getKeyByHeader(
                \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_NAME,
                $headers
            )]
        );
        return $model;
    }
}
