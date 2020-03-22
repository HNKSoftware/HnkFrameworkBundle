<?php

namespace Hnk\HnkFrameworkBundle\Twig;

use Hnk\HnkFrameworkBundle\Seo\SeoManager;
use Hnk\HnkFrameworkBundle\Seo\SeoPage;
use Hnk\HnkFrameworkBundle\Seo\SeoRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SeoExtension extends AbstractExtension
{
    /**
     * @var SeoManager
     */
    private $seoManager;

    /**
     * @var SeoRenderer
     */
    private $seoRenderer;

    public function __construct(SeoManager $seoManager)
    {
        $this->seoManager = $seoManager;
        $this->seoRenderer = new SeoRenderer($seoManager);
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('hnk_seo', [$this->seoRenderer, 'renderSeo'], ['is_safe' => ['html']]),
            new TwigFunction('hnk_seo_title', [$this->seoRenderer, 'renderSeoTitle'], ['is_safe' => ['html']]),
            new TwigFunction('hnk_seo_description', [$this->seoRenderer, 'renderSeoDescription'], ['is_safe' => ['html']]),
            new TwigFunction('hnk_seo_keywords', [$this->seoRenderer, 'renderSeoKeywords'], ['is_safe' => ['html']]),
            new TwigFunction('hnk_seo_page', [$this, 'getSeoPage'], ['is_safe' => ['html']]),
        );
    }

    /**
     * Returns current SeoPage
     *
     * @return SeoPage
     */
    public function getSeoPage(): SeoPage
    {
        return $this->seoManager->getSeoPage();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'hnk_seo_extension';
    }
}
