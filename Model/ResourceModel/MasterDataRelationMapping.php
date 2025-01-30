<?php

namespace PinBlooms\MasterData\Model\ResourceModel;

class MasterDataRelationMapping extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('pinblooms_masterdata_relation_mapping', 'product_type_value');
    }
}
