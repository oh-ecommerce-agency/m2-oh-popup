<?php

namespace OH\Popup\Controller\Cookie;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Newsletter\Model\Subscriber;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Store\Model\StoreManagerInterface;
use OH\Popup\Model\Cookie;

class Validate implements HttpGetActionInterface
{
    /**
     * @var Cookie
     */
    private $cookie;

    /**
     * Subscriber factory
     *
     * @var SubscriberFactory
     */
    private $subscriberFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    private $resultFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        RequestInterface $request,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        StoreManagerInterface $storeManager,
        SubscriberFactory $subscriberFactory,
        Cookie $cookie
    ) {
        $this->request = $request;
        $this->storeManager = $storeManager;
        $this->subscriberFactory = $subscriberFactory;
        $this->cookie = $cookie;
        $this->resultFactory = $resultFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $needToRender = false;

        if ($this->cookie->getCookie()) {
            return $result->setData(['need_render' => $needToRender]);
        }

        //Check already subscribed
        if ($email = $this->request->getParam('email')) {
            $subscriber = $this->subscriberFactory->create()
                ->loadBySubscriberEmail(
                    $email,
                    $this->storeManager->getWebsite()->getId()
                );

            if (!$subscriber->getId() || ($subscriber->getId() && $subscriber->getSubscriberStatus() != Subscriber::STATUS_SUBSCRIBED)) {
                $needToRender = true;
            }
        } else {
            //Guest always receive popup first time
            $needToRender = true;
        }

        return $result->setData(['need_render' => $needToRender]);
    }
}
