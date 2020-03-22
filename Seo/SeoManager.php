<?php

namespace Hnk\HnkFrameworkBundle\Seo;

class SeoManager
{
    /**
     * @var SeoPage
     */
    protected $seoPage;

    /**
     * @param string $defaultTitle
     */
    public function __construct($defaultTitle)
    {
        $this->seoPage = new SeoPage($defaultTitle);
    }

    /**
     * @return SeoPage
     */
    public function getSeoPage(): SeoPage
    {
        return $this->seoPage;
    }
}
