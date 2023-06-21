<?php

declare(strict_types=1);

namespace OH\Popup\Setup\Patch\Data;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CreateBlock implements DataPatchInterface
{
    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    private $blockFactory;

    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepo;

    public function __construct(
        private BlockRepositoryInterface $blockRepository,
        \Magento\Cms\Model\BlockFactory $blockFactory
    ) {
        $this->blockFactory = $blockFactory;
        $this->blockRepo = $this->blockRepository;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    /**
     * @return CreateBlock|void
     */
    public function apply()
    {
        $cmsBlockData = [
            'title' => 'Popup - Template 1',
            'identifier' => 'popup-template1',
            'content' => <<<HTML
                            <style>#html-body [data-pb-style=A46I7JP],#html-body [data-pb-style=ESSQG8U]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=ESSQG8U]{justify-content:flex-start;display:flex;flex-direction:column}#html-body [data-pb-style=A46I7JP]{align-self:stretch}#html-body [data-pb-style=FPNRBDY]{display:flex;width:100%}#html-body [data-pb-style=N6BEN7X]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=Q4VI180]{border-style:none}#html-body [data-pb-style=K9KJFE8],#html-body [data-pb-style=U01IKKX]{max-width:100%;height:auto}#html-body [data-pb-style=RDL25MM]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}@media only screen and (max-width: 768px) { #html-body [data-pb-style=Q4VI180]{border-style:none} }</style><div data-content-type="row" data-appearance="contained" data-element="main"><div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="ESSQG8U"><div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="2" data-element="main" data-pb-style="A46I7JP"><div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="FPNRBDY"><div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="N6BEN7X"><figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="Q4VI180"><img class="pagebuilder-mobile-hidden" src="{{media url=wysiwyg/Rectangle_19.png}}" alt="" title="" data-element="desktop_image" data-pb-style="U01IKKX"><img class="pagebuilder-mobile-only" src="{{media url=wysiwyg/Rectangle_19.png}}" alt="" title="" data-element="mobile_image" data-pb-style="K9KJFE8"></figure></div><div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="RDL25MM"><div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="newsletter-container"&gt;
             {{block class="Magento\\Newsletter\\Block\\Subscribe" name="form.subscribe.popup" template="Magento_Newsletter::subscribe.phtml" ifconfig="newsletter/general/active"}}
            &lt;/div&gt;</div></div></div></div></div></div>
            HTML,
            'is_active' => 1,
            'stores' => [0],
            'sort_order' => 0
        ];

        $cmsBlock = $this->blockFactory->create()->setData($cmsBlockData);
        $this->blockRepository->save($cmsBlock);
    }
}
