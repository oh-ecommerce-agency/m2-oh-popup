<?php

namespace OH\Popup\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory;

class Rule implements OptionSourceInterface
{

    /**
     * @var CollectionFactory
     */
    private $ruleCollectionFactory;

    /**
     * @var array
     */
    private $options;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->ruleCollectionFactory = $collectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        if ($this->options) {
            return $this->options;
        }

        $rules = $this->ruleCollectionFactory->create();

        foreach ($rules->getItems() as $rule) {
            $this->options[] = [
                'label' => $rule->getName(),
                'value' => $rule->getId()
            ];
        }

        return $this->options;
    }
}
