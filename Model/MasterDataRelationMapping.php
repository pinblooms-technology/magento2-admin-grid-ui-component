<?php

namespace PinBlooms\MasterData\Model;

class MasterDataRelationMapping extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Contruct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(\PinBlooms\MasterData\Model\ResourceModel\MasterDataIssueType::class);
    }
}
