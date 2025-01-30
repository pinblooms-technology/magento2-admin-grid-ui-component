<?php

namespace PinBlooms\MasterData\Model\ResourceModel\MasterDataIssueType;

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
            \PinBlooms\MasterData\Model\MasterDataIssueType::class,
            \PinBlooms\MasterData\Model\ResourceModel\MasterDataIssueType::class
        );
        $this->_setIdFieldName('entity_id');
    }
}
