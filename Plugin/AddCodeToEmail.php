<?php

namespace OH\Popup\Plugin;

use OH\Popup\Model\ConfigProvider;

class AddCodeToEmail
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    private $inlineTranslation;

    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var \OH\Popup\Model\CouponResolver
     */
    private $couponResolver;

    public function __construct(
        \OH\Popup\Model\CouponResolver $couponResolver,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        ConfigProvider $configProvider
    ) {
        $this->couponResolver = $couponResolver;
        $this->configProvider = $configProvider;
        $this->scopeConfig = $scopeConfig;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
    }

    /**
     * Attach coupon code to email
     */
    public function aroundSendConfirmationSuccessEmail(
        \Magento\Newsletter\Model\Subscriber $subscribe,
        callable $proceed
    ) {
        if (!$this->configProvider->isEnabled() || !$this->configProvider->isSendCouponEnabled()) {
            return $proceed();
        }

        if (!$coupon = $this->couponResolver->getCouponCode()) {
            return $proceed();
        }

        //Check first time subscribed to avoid trigger several coupons: CHE-1483
        if ($subscribe->isSubscribed() && $subscribe->getOrigData('subscriber_status') == \Magento\Newsletter\Model\Subscriber::STATUS_SUBSCRIBED) {
            return false;
        }

        $this->inlineTranslation->suspend();

        $this->transportBuilder->setTemplateIdentifier(
            $this->configProvider->getEmailTemplate($this->storeManager->getStore()->getCode())
        )->setTemplateOptions(
            [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $this->storeManager->getStore()->getId(),
            ]
        )->setTemplateVars(
            ['subscriber' => $subscribe, 'coupon_code' => $coupon]
        )->setFromByScope(
            $this->scopeConfig->getValue(
                $subscribe::XML_PATH_SUCCESS_EMAIL_IDENTITY,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            )
        )->addTo(
            $subscribe->getSubscriberEmail(),
            $subscribe->getSubscriberFullName()
        );
        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
        return true;
    }
}