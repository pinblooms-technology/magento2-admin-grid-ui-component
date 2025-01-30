<?php

namespace PinBlooms\MasterData\Model;

use Magento\Framework\Model\AbstractModel;

class ValueMapping extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('PinBlooms\MasterData\Model\ResourceModel\ValueMapping');
    }
}
