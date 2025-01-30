<?php

namespace PinBlooms\MasterData\Model\ResourceModel;

class MasterDataProductType extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('pinblooms_masterdata_product_type', 'entity_id');
    }
}
