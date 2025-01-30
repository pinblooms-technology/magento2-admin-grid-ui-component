<?php

namespace PinBlooms\MasterData\Ui\Component\Listing\Column;

use Magento\Framework\Data\OptionSourceInterface;

class MIssueTypeOptions implements OptionSourceInterface
{
    /**
     * MIssueTypeOptions function
     *
     * @return void
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'type1', 'label' => __('Type 1')],
            ['value' => 'type2', 'label' => __('Type 2')],
            ['value' => 'type3', 'label' => __('Type 3')],
        ];
    }
}
