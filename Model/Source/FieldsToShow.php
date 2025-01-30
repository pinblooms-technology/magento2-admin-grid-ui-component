<?php

namespace PinBlooms\MasterData\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class FieldsToShow
 *
 * PinBlooms\MasterData\Model\Source
 */
class FieldsToShow extends AbstractSource
{
    /**
     * GetALlOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        return [
            [
                'value' => \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_SKU,
                'label' => __('SKU'),
            ],
            [
                'value' => \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_PRODUCT_TYPE,
                'label' => __('Product type'),
            ],
            [
                'value' => \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_CATEGORY,
                'label' => __('Category'),
            ],
            [
                'value' => \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_ISSUE_TYPE,
                'label' => __('Issue Type'),
            ],
            [
                'value' => \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_NAME,
                'label' => __('Name'),
            ]
        ];
    }
}
