<?php


namespace Hnk\HnkFrameworkBundle\Seo;


class SeoRenderer
{
    /**
     * @var SeoManager
     */
    private $seoManager;

    public function __construct(SeoManager $seoManager)
    {
        $this->seoManager = $seoManager;
    }

    /**
     * Renders all seo tags
     *
     * @return string
     */
    public function renderSeo(): string
    {
        $html = $this->renderSeoTitle();

        if ($this->seoManager->getSeoPage()->hasDescription()) {
            $html .= $this->renderSeoDescription();
        }

        if ($this->seoManager->getSeoPage()->hasKeywords()) {
            $html .= $this->renderSeoKeywords();
        }

        return $html;
    }

    /**
     * Returns title html tag
     *
     * @return string
     */
    public function renderSeoTitle(): string
    {
        return sprintf(
            '<title>%s</title>',
            $this->seoManager->getSeoPage()->getTitle()
        );
    }

    /**
     * Returns description html tag
     *
     * @return string
     */
    public function renderSeoDescription(): string
    {
        return sprintf(
            '<meta name="description" content="%s">',
            $this->seoManager->getSeoPage()->getDescription()
        );
    }

    /**
     * Returns keywords html tag
     *
     * @return string
     */
    public function renderSeoKeywords(): string
    {
        return sprintf(
            '<meta name="keywords" content="%s">',
            $this->seoManager->getSeoPage()->getKeywords()
        );
    }

}