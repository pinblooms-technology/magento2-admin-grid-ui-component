<?php

namespace PinBlooms\MasterData\Model;

class MasterDataIssueType extends \Magento\Framework\Model\AbstractModel
{
    public const PINBLOOMS_MASTERDATA_M_ISSUE_TYPE = 'm_issue_type';

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
