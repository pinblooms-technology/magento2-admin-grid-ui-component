<?php

namespace PinBlooms\MasterData\Helper;

use Magento\Framework\App\Helper\Context;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfigManager;

    /**
     * Config constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
        $this->scopeConfigManager = $context->getScopeConfig();
    }

    /**
     * GetFieldsToShowOnFrontend
     *
     * @return mixed|string
     */
    public function getFieldsToShowOnFrontend()
    {
        $value = $this->scopeConfigManager->getValue('prodauthenticonfiguration/setting/view_on_frontend');
        if (empty($value)) {
            $value = \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_SKU;
        }
        return $value;
    }
}
