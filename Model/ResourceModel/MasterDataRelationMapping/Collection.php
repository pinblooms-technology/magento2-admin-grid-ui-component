<?php

namespace PinBlooms\MasterData\Model\ResourceModel\MasterDataRelationMapping;

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
            \PinBlooms\MasterData\Model\MasterDataRelationMapping::class,
            \PinBlooms\MasterData\Model\ResourceModel\MasterDataRelationMapping::class
        );
    }
}
