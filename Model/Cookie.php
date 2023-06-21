<?php

namespace OH\Popup\Model;

class Cookie
{
    const COOKIE_NAME = 'oh_popup';

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface CookieManagerInterface
     */
    private $cookieManager;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    public function __construct(
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
    ) {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
    }

    public function setCookie($data)
    {
        if ($data) {
            $this->deleteCookie();
            $publicCookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata();
            $publicCookieMetadata->setDuration(3600 * 24);
            $publicCookieMetadata->setPath('/');
            $publicCookieMetadata->setHttpOnly(false);

            try {
                $this->cookieManager->setPublicCookie(
                    self::COOKIE_NAME,
                    json_encode($data),
                    $publicCookieMetadata
                );
            } catch (\Exception $exception) {
                return false;
            }
        }

        return false;
    }

    public function getCookie()
    {
        return $this->cookieManager->getCookie(self::COOKIE_NAME);
    }

    public function deleteCookie()
    {
        $publicCookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata();
        $publicCookieMetadata->setPath('/');
        $this->cookieManager->deleteCookie(self::COOKIE_NAME, $publicCookieMetadata);
    }
}
