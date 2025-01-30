<?php

namespace PinBlooms\MasterData\Model\ResourceModel\ValueMapping;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define the resource model & the model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \PinBlooms\MasterData\Model\ValueMapping::class,
            \PinBlooms\MasterData\Model\ResourceModel\ValueMapping::class
        );
    }
}
