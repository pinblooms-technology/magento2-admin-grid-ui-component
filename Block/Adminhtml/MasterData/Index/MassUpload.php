<?php

namespace PinBlooms\MasterData\Block\Adminhtml\MasterData\Index;

class MassUpload extends \Magento\Backend\Block\Template
{
    /**
     * @var $idsCount
     */
    public $idsCount;
    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    public $session;

    /**
     * MassUpload constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Session\SessionManagerInterface $session
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Session\SessionManagerInterface $session,
        $data = []
    ) {
        parent::__construct($context, $data);
        $this->session = $session;
        $this->idsCount = $session->getIdsCount();
    }

    /**
     * GetActionUrl
     *
     * @return string
     */
    public function getActionUrl()
    {
        return $this->getUrl('*/*/upload');
    }
}
