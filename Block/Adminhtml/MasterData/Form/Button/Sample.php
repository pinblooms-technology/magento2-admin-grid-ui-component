<?php

namespace PinBlooms\MasterData\Block\Adminhtml\MasterData\Form\Button;

use Magento\Backend\Block\Widget\Container;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Sample extends Container implements ButtonProviderInterface
{
    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Download Sample Csv File'),
            'on_click' => sprintf("location.href = '%s';", $this->getSampleFileUrl()),
            'class' => 'secondary',
            'sort_order' => 10
        ];
    }

    /**
     * GetSampleFileUrl
     *
     * @return string
     */
    public function getSampleFileUrl()
    {
        return $this->getUrl('*/*/sample');
    }
}
