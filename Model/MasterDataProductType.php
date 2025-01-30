<?php

namespace PinBlooms\MasterData\Model;

class MasterDataProductType extends \Magento\Framework\Model\AbstractModel
{
    public const PINBLOOMS_MASTERDATA_M_PRODUCT_TYPE = 'm_product_type';

    /**
     * Contruct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(\PinBlooms\MasterData\Model\ResourceModel\MasterDataProductType::class);
    }
}
