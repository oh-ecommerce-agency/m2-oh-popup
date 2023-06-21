<?php

namespace OH\Popup\Controller\Subscribe;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Newsletter\Model\Subscriber;

class NewAction extends \Magento\Newsletter\Controller\Subscriber\NewAction
{
    /**
     * @inheritDoc
     */
    public function execute()
    {
        $message = '';
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        if ($this->getRequest()->isPost() && $this->getRequest()->isAjax() && $this->getRequest()->getPost('email')) {
            $email = (string)$this->getRequest()->getPost('email');

            try {
                $this->messageManager->getMessages(true);
                $this->validateEmailFormat($email);
                $this->validateGuestSubscription();
                $this->validateEmailAvailable($email);

                $subscriber = $this->_subscriberFactory->create()->loadByEmail($email);
                if ($subscriber->getId()
                    && (int)$subscriber->getSubscriberStatus() === Subscriber::STATUS_SUBSCRIBED
                ) {
                    $message = __('This email address is already subscribed.');
                    $this->messageManager->addWarningMessage($message);

                    return $result->setData([
                        'success' => true,
                        'message' => $message
                    ]);
                }

                $this->messageManager->addSuccessMessage(__('Thanks for subscribe, we sent the code by email'));
//                $status = (int)$this->_subscriberFactory->create()->subscribe($email);
                return $result->setData([
                    'success' => true,
                    'message' => $this->getSuccessMessage(1)
                ]);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong with the subscription.'));
            }
        }

        return $result->setData([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Get success message
     *
     * @param int $status
     * @return Phrase
     */
    private function getSuccessMessage(int $status): Phrase
    {
        if ($status === Subscriber::STATUS_NOT_ACTIVE) {
            return __('The confirmation request has been sent.');
        }

        return __('Thank you for your subscription.');
    }
}