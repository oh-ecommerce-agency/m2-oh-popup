<?php

namespace OH\Popup\Model;

use Magento\SalesRule\Api\Data\CouponGenerationSpecInterfaceFactory;

class CouponResolver
{
    /**
     * @var \Magento\SalesRule\Model\Service\CouponManagementService
     */
    private $couponManagement;

    /**
     * @var CouponGenerationSpecInterfaceFactory
     */
    private $generationSpecFactory;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    private $logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\SalesRule\Model\Service\CouponManagementService $couponManagement,
        \Magento\SalesRule\Api\Data\CouponGenerationSpecInterfaceFactory $couponGenerationSpecFactory,
        ConfigProvider $configProvider
    ) {
        $this->logger = $logger;
        $this->couponManagement = $couponManagement;
        $this->generationSpecFactory = $couponGenerationSpecFactory;
        $this->configProvider = $configProvider;
    }

    /**
     * Generate new coupon code
     *
     * @return bool|mixed|string
     */
    public function getCouponCode()
    {
        if ($rule = $this->configProvider->getCouponRule()) {
            $data = [
                'rule_id' => $rule,
                'quantity' => 1,
                'length' => $this->configProvider->getCouponLength() ?: 8,
                'suffix' => $this->configProvider->getCouponSuffix(),
                'prefix' => $this->configProvider->getCouponPrefix()
            ];

            try {
                $couponSpec = $this->generationSpecFactory->create(['data' => $data]);
                $generatedCoupons = $this->couponManagement->generate($couponSpec);

                if ($generatedCoupons) {
                    return reset($generatedCoupons);
                }
            } catch (\Exception $exception) {
                $this->logger->error('OH_Popup: error generating coupon code: ' . $exception->getMessage());
            }
        }

        return false;
    }
}