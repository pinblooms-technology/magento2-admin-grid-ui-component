<?php

namespace PinBlooms\MasterData\Model;

class MasterData extends \Magento\Framework\Model\AbstractModel
{
    public const PINBLOOMS_MASTERDATA_ID = 'id';
    public const PINBLOOMS_MASTERDATA_M_SKU = 'm_sku';
    public const PINBLOOMS_MASTERDATA_M_PRODUCT_TYPE = 'm_product_type';
    public const PINBLOOMS_MASTERDATA_M_CATEGORY = 'm_category';
    public const PINBLOOMS_MASTERDATA_M_ISSUE_TYPE = 'm_issue_type';
    public const PINBLOOMS_MASTERDATA_M_NAME = 'm_name';

    /**
     * Contruct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(\PinBlooms\MasterData\Model\ResourceModel\MasterData::class);
    }
}
