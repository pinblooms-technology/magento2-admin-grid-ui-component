<?php

namespace PinBlooms\MasterData\Block\MasterData;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \PinBlooms\MasterData\Model\ResourceModel\MasterData
     */
    public $masterDataResource;
    /**
     * @var \PinBlooms\MasterData\Model\MasterDataFactory
     */
    public $masterDataFactory;
    /**
     * @var \PinBlooms\MasterData\Helper\Config
     */
    public $configHelper;

    /**
     * Index constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \PinBlooms\MasterData\Model\MasterDataFactory $masterDataFactory
     * @param MasterData $masterDataResource
     * @param \PinBlooms\MasterData\Helper\Config $configHelper
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \PinBlooms\MasterData\Model\MasterDataFactory $masterDataFactory,
        \PinBlooms\MasterData\Model\ResourceModel\MasterData $masterDataResource,
        \PinBlooms\MasterData\Helper\Config $configHelper
    ) {
        parent::__construct($context);
        $this->masterDataFactory = $masterDataFactory;
        $this->masterDataResource = $masterDataResource;
        $this->configHelper = $configHelper;
    }

    /**
     * GetQrValueFromRequest
     *
     * @return mixed
     */
    public function getQrValueFromRequest()
    {
        return $this->getRequest()->getParam('qr', null);
    }

    /**
     * GetDataByQrValue
     *
     * @param string $mskuValue
     *
     * @return array
     */
    public function getDataByQrValue($mskuValue = '')
    {
        if (!empty($mskuValue)) {
            $model = $this->masterDataFactory->create();
            $this->masterDataResource->load(
                $model,
                $mskuValue,
                \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_SKU
            );
            if (!empty($model->getData())) {
                return $model->getData();
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    /**
     * GeFlagToShowFieldsByFieldName
     *
     * @param string $fieldName
     *
     * @return bool
     */
    public function getFlagToShowFieldsByFieldName(
        $fieldName = \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_SKU
    ) {
        $keys = explode(',', $this->configHelper->getFieldsToShowOnFrontend());
        if (in_array($fieldName, $keys)) {
            return true;
        } else {
            return false;
        }
    }
}
