<?php

namespace PinBlooms\MasterData\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ValueMapping extends AbstractDb
{
    /**
     * Construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('pinblooms_masterdata_value_mapping', 'entity_id');
    }

    /**
     * Truncate Table function
     *
     * @return void
     */
    public function truncateTable()
    {
        if ($this->getConnection()->getTransactionLevel() > 0) {
            // $this->getConnection()->delete($this->getMainTable());
        } else {
            $this->getConnection()->truncateTable($this->getMainTable());
        }

        return $this;
    }

    /**
     * Lookup Variety Names
     *
     * @param array $cases
     * @param array $ids
     * @return array
     */
    public function lookupUpdateParentData($cases, $ids)
    {
        $adapter = $this->getConnection();

        $tableName = $adapter->getTableName('pinblooms_masterdata_value_mapping');

        $query = "
                UPDATE {$tableName} SET
                    parent_id = CASE entity_id
                        {$cases['parent_id']}
                        ELSE parent_id END
                WHERE entity_id IN ({$ids})
            ";
        $adapter->query($query);

        return $this;
    }
}
