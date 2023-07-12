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
            <style>#html-body [data-pb-style=A0IPKGA],#html-body [data-pb-style=WEBCLFH]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=A0IPKGA]{justify-content:flex-start;display:flex;flex-direction:column}#html-body [data-pb-style=WEBCLFH]{align-self:stretch}#html-body [data-pb-style=PHIG7B1]{display:flex;width:100%}#html-body [data-pb-style=U0L219T],#html-body [data-pb-style=XPJ9W0I]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}</style><div data-content-type="row" data-appearance="contained" data-element="main"><div class="template1" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="A0IPKGA"><div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="2" data-element="main" data-pb-style="WEBCLFH"><div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="PHIG7B1"><div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{\&quot;desktop_image\&quot;:\&quot;{{media url=wysiwyg/Malibu.png}}\&quot;}" data-element="main" data-pb-style="U0L219T"><div data-content-type="text" data-appearance="default" data-element="main"><p>¡Se parte de nuestra comunidad y mantente informado al instante!</p></div></div><div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="XPJ9W0I"><div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="newsletter-container"&gt;
             {{block class="Magento\Newsletter\Block\Subscribe" name="form.subscribe.popup" template="Magento_Newsletter::subscribe.phtml" ifconfig="newsletter/general/active"}}
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
