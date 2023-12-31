<?php

namespace OH\Popup\Plugin\CustomerData;

use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Customer\CustomerData\Customer;

class CustomerPlugin
{
    /**
     * @var CurrentCustomer
     */
    private $currentCustomer;

    /**
     * @param CurrentCustomer $currentCustomer
     */
    public function __construct(
        CurrentCustomer $currentCustomer
    ) {
        $this->currentCustomer = $currentCustomer;
    }

    /**
     * @param Customer $subject
     * @param string[] $result
     * @return string[]
     */
    public function afterGetSectionData($subject, $result)
    {
        if (!$this->currentCustomer->getCustomerId()) {
            return $result;
        }

        $customer = $this->currentCustomer->getCustomer();
        $result['email'] = $customer->getEmail();
        return $result;
    }
}
