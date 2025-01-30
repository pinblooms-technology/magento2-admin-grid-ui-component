<?php

namespace PinBlooms\MasterData\Model\ResourceModel\MasterDataProductType;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Construct function
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(
            \PinBlooms\MasterData\Model\MasterDataProductType::class,
            \PinBlooms\MasterData\Model\ResourceModel\MasterDataProductType::class
        );
        $this->_setIdFieldName('entity_id');
    }
}
