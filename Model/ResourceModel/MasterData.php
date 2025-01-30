<?php

namespace PinBlooms\MasterData\Model\ResourceModel;

/**
 * Class MasterData
 * PinBlooms\MasterData\Model\ResourceModel
 */
class MasterData extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('pinblooms_masterdata', 'entity_id');
    }

    /**
     * Perform operations after object load
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getEntityId()) {

            $package_id =  $this->lookupPackageIds($object->getEntityId());
            $object->setPackageId($package_id);
            if (!empty($package_id)) {
                $packageName =  $this->lookupPackageName($package_id);
                $object->setPackageName($packageName);
            }

            // Load Variety IDs and Names
            $variety_id = $this->lookupVarietyIds($object->getEntityId());
            $object->setVarietyId($variety_id);
            if (!empty($variety_id)) {
                $varietyName = $this->lookupVarietyName($variety_id);
                $object->setVarietyName($varietyName);
            }
        }
        return parent::_afterLoad($object);
    }

    /**
     * LookupPackageIds Function
     *
     * @param int $id
     * @return array
     */
    public function lookupPackageIds($id)
    {
        return $this->_lookupIds($id, 'pinblooms_masterdata_sku_package', 'package_id');
    }

    /**
     * LookupPackageName Function
     *
     * @param array $ids
     * @return array
     */
    public function lookupPackageName($ids)
    {

        $adapter = $this->getConnection();
        $select = $adapter->select()->from(
            $this->getTable('pinblooms_masterdata_package'),
            'title'
        )->where(
            'entity_id IN (?)',
            $ids
        );

        return $adapter->fetchCol($select);
    }

    /**
     * Lookup Variety IDs
     *
     * @param int $id
     * @return array
     */
    public function lookupVarietyIds($id)
    {
        return $this->_lookupIds($id, 'pinblooms_masterdata_sku_variety', 'variety_id');
    }

    /**
     * Lookup Variety Names
     *
     * @param array $ids
     * @return array
     */
    public function lookupVarietyName($ids)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()->from(
            $this->getTable('pinblooms_masterdata_variety'),
            'title'
        )->where(
            'entity_id IN (?)',
            $ids
        );

        return $adapter->fetchCol($select);
    }

    /**
     * Get ids to which specified item is assigned
     *
     * @param int $id
     * @param string $tableName
     * @param string $field
     * @return array
     */
    protected function _lookupIds($id, $tableName, $field)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()->from(
            $this->getTable($tableName),
            $field
        )->where(
            'master_id = ?',
            (int)$id
        );

        return $adapter->fetchCol($select);
    }

    /**
     * Get rows to which specified item is assigned
     *
     * @param int $id
     * @param string $tableName
     * @param string $field
     * @return array
     */
    protected function _lookupAll($id, $tableName, $field)
    {
        $adapter = $this->getConnection();

        $select = $adapter->select()->from(
            $this->getTable($tableName),
            $field
        )->where(
            'entity_id = ?',
            (int)$id
        );

        return $adapter->fetchAll($select);
    }
}
