<?php

namespace PinBlooms\MasterData\Model\ResourceModel\MasterData;

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
            \PinBlooms\MasterData\Model\MasterData::class,
            \PinBlooms\MasterData\Model\ResourceModel\MasterData::class
        );
        $this->_setIdFieldName('entity_id');
    }

    /**
     * Collection function
     *
     * @return void
     */
    protected function _initSelect()
    {

        $this->getSelect()
            ->from(['main_table' => $this->getMainTable()]);
        $this->addFilterToMap('entity_id', 'main_table.entity_id');

        return $this;
    }
}
