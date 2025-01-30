<?php

namespace PinBlooms\MasterData\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
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
     * Data function
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\File\Csv $csv
     * @param \PinBlooms\MasterData\Model\MasterDataFactory $masterDataFactory
     * @param \PinBlooms\MasterData\Model\ResourceModel\MasterData $masterDataResource
     * @param \PinBlooms\MasterData\Model\ResourceModel\MasterData\CollectionFactory $masterDataCollection
     * @param \PinBlooms\MasterData\Logger\Logger $logger
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\File\Csv $csv,
        \PinBlooms\MasterData\Model\MasterDataFactory $masterDataFactory,
        \PinBlooms\MasterData\Model\ResourceModel\MasterData $masterDataResource,
        \PinBlooms\MasterData\Model\ResourceModel\MasterData\CollectionFactory
        $masterDataCollection,
        \PinBlooms\MasterData\Logger\Logger $logger
    ) {
        parent::__construct($context);
        $this->csv = $csv;
        $this->masterDataFactory = $masterDataFactory;
        $this->masterDataResource = $masterDataResource;
        $this->masterDataCollection = $masterDataCollection;
        $this->logger = $logger;
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
