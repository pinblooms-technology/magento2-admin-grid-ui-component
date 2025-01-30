<?php

namespace PinBlooms\MasterData\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class Status
 * PinBlooms\MasterData\Model\Source\Status
 */
class Status extends AbstractSource
{
    // Custom Status
    public const STATUS_INACTIVE = '0';
    public const STATUS_ACTIVE = '1';

    /**
     * GetAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        return [
            [
                'value' => self::STATUS_ACTIVE,
                'label' => __('Active'),
            ],
            [
                'value' => self::STATUS_INACTIVE,
                'label' => __('Inactive'),
            ]
        ];
    }
}
