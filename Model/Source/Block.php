<?php

namespace OH\Popup\Model\Source;

class Block extends \Magento\Cms\Model\Config\Source\Block
{
    /**
     * @var array
     */
    private $options;

    /**
     * Filter options
     *
     * @inheritDoc
     */
    public function toOptionArray()
    {
        if ($this->options) {
            return $this->options;
        }

        $options = parent::toOptionArray();
        $finalOps = [];

        foreach ($options as $option) {
            if (!empty($option['value']) && str_contains($option['value'], 'popup-template')) {
                $finalOps[] = $option;
            }
        }

        $this->options = $finalOps;
        return $this->options;
    }
}
