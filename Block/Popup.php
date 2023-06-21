<?php

namespace OH\Popup\Block;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Framework\View\Element\Template;
use OH\Popup\Model\ConfigProvider;

class Popup extends Template
{
    const DEFAULT_TEMPLATE_ID = 'popup-template1';

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    private $filterProvider;

    private $block;

    public function __construct(
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        BlockRepositoryInterface $blockRepository,
        ConfigProvider $configProvider,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configProvider = $configProvider;
        $this->blockRepository = $blockRepository;
        $this->filterProvider = $filterProvider;
    }

    public function isEnabled()
    {
        return $this->configProvider->isEnabled();
    }

    public function getContent()
    {
        return $this->getContentFromBlock();
    }

    public function getContentFromBlock()
    {
        $blockId = $this->configProvider->getBlockId();

        try {
            $block = $this->blockRepository->getById($blockId ?: self::DEFAULT_TEMPLATE_ID);
        } catch (\Exception $exception) {
            return '';
        }


        if ($block->getId()) {
            $this->block = $block;
            $storeId = $this->_storeManager->getStore()->getId();
            return $this->filterProvider->getBlockFilter()->setStoreId($storeId)->filter($block->getContent());
        }

        return '';
    }

    public function isSendCouponEnabled()
    {
        return $this->configProvider->isSendCouponEnabled();
    }

    public function getCssStyles()
    {
        return $this->configProvider->getCssStyles();
    }

    public function getJsonConfig()
    {
        return json_encode([
            'cookie_validate_url' => $this->getUrl('pe_popup/cookie/validate'),
            'subscribe_url' => $this->getUrl('pe_popup/subscribe/new'),
            'delay_time' => $this->configProvider->getDelay() ?: 20000,
            'block_id' => $this->block ? $this->block->getIdentifier() : ''
        ]);
    }
}
