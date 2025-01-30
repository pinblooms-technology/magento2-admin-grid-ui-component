<?php

namespace PinBlooms\MasterData\Block\Adminhtml\MasterData\Form\Button;

use Magento\Backend\Block\Widget\Container;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Back extends Container implements ButtonProviderInterface
{
    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    /**
     * GetBackUrl
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/index');
    }
}
