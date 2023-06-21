<?php

namespace OH\Popup\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Url;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use OH\Popup\Model\Config\Backend\Image;

class ConfigProvider
{
    /**
     * @var string
     */
    const XML_CONFIG_PATH_ENABLED = 'oh_popup/settings/enabled';

    /**
     * @var string
     */
    const XML_CONFIG_PATH_SEND_COUPON = 'oh_popup/settings/send_coupon';

    /**
     * @var string
     */
    const XML_CONFIG_PATH_BLOCK_ID = 'oh_popup/settings/block_id';

    /**
     * @var string
     */
    const XML_CONFIG_PATH_COUPON_RULE = 'oh_popup/settings/coupon_rule';

    /**
     * @var string
     */
    const XML_CONFIG_PATH_COUPON_RULE_LENGTH = 'oh_popup/settings/coupon_rule';

    /**
     * @var string
     */
    const XML_CONFIG_PATH_COUPON_LENGTH = 'oh_popup/settings/coupon_length';

    /**
     * @var string
     */
    const XML_CONFIG_PATH_COUPON_PREFIX = 'oh_popup/settings/coupon_prefix';

    /**
     * @var string
     */
    const XML_CONFIG_PATH_COUPON_SUFFIX = 'oh_popup/settings/coupon_suffix';

    /**
     * @var string
     */
    const XML_CONFIG_PATH_EMAIL_TEMPLATE = 'oh_popup/settings/email_template';

    /**
     * @var string
     */
    const XML_CONFIG_PATH_MODAL_CONTENT = 'oh_popup/settings/content';

    /**
     * @var string
     */
    const XML_CONFIG_PATH_CSS_STYLES = 'oh_popup/settings/css_styles';

    /**
     * @var string
     */
    const XML_CONFIG_PATH_DELAY_SECONDS = 'oh_popup/settings/delay';

    /**
     * @var string
     */
    const XML_CONFIG_PATH_IMAGE = 'oh_popup/settings/image';

    /**
     * @var ScopeInterface
     */
    private $scopeInterface;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    private $mediaDirectory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var UrlInterface
     */
    private $url;

    public function __construct(
        UrlInterface $url,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeInterface
    ) {
        $this->url = $url;
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
        $this->scopeInterface = $scopeInterface;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    /**
     * Check if module is enabled
     *
     * @return string
     */
    public function isEnabled()
    {
        return $this->scopeInterface->isSetFlag(self::XML_CONFIG_PATH_ENABLED, ScopeInterface::SCOPE_STORES);
    }

    /**
     * Get content
     *
     * @return bool
     */
    public function getContent()
    {
        return $this->scopeInterface->getValue(self::XML_CONFIG_PATH_MODAL_CONTENT, ScopeInterface::SCOPE_STORES);
    }

    /**
     * Check if subscription should be renderer on modal
     *
     * @return bool
     */
    public function getCouponRule()
    {
        return $this->scopeInterface->getValue(self::XML_CONFIG_PATH_COUPON_RULE, ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * @return bool
     */
    public function getCouponLength()
    {
        return $this->scopeInterface->getValue(self::XML_CONFIG_PATH_COUPON_LENGTH, ScopeInterface::SCOPE_STORES);
    }

    /**
     * @return bool
     */
    public function getCouponPrefix()
    {
        return $this->scopeInterface->getValue(self::XML_CONFIG_PATH_COUPON_PREFIX, ScopeInterface::SCOPE_STORES);
    }

    /**
     * @return bool
     */
    public function getCouponSuffix()
    {
        return $this->scopeInterface->getValue(self::XML_CONFIG_PATH_COUPON_SUFFIX, ScopeInterface::SCOPE_STORES);
    }

    /**
     * Check if send idscount is enabled
     *
     * @return bool
     */
    public function isSendCouponEnabled()
    {
        return $this->scopeInterface->isSetFlag(self::XML_CONFIG_PATH_SEND_COUPON, ScopeInterface::SCOPE_STORES);
    }

    /**
     * Get template
     *
     * @return bool
     */
    public function getEmailTemplate($store)
    {
        return $this->scopeInterface->getValue(self::XML_CONFIG_PATH_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORES,
            $store);
    }

    /**
     * Get css
     *
     * @return bool
     */
    public function getCssStyles()
    {
        return $this->scopeInterface->getValue(self::XML_CONFIG_PATH_CSS_STYLES, ScopeInterface::SCOPE_STORES);
    }

    /**
     * Get block id
     *
     * @return bool
     */
    public function getBlockId()
    {
        return $this->scopeInterface->getValue(self::XML_CONFIG_PATH_BLOCK_ID, ScopeInterface::SCOPE_STORES);
    }

    /**
     * Get css
     *
     * @return bool
     */
    public function getDelay()
    {
        return $this->scopeInterface->getValue(self::XML_CONFIG_PATH_DELAY_SECONDS, ScopeInterface::SCOPE_STORES);
    }


    /**
     * Get background image
     *
     * @return bool
     */
    public function getBackgroundImage()
    {
        $imageName = $this->scopeInterface->getValue(self::XML_CONFIG_PATH_IMAGE, ScopeInterface::SCOPE_STORES);

        if ($imageName) {
            $pathImage = $this->mediaDirectory->getRelativePath(Image::UPLOAD_DIR . DIRECTORY_SEPARATOR . $imageName);
            return $this->url->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]) . $pathImage;
        }

        return false;
    }
}
