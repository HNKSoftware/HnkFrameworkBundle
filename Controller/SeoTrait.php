<?php

namespace Hnk\HnkFrameworkBundle\Controller;

use Hnk\HnkFrameworkBundle\Seo\SeoManager;
use Hnk\HnkFrameworkBundle\Seo\SeoPage;

trait SeoTrait
{
    /**
     *
     *
     * @return array
     */
    public static function getSeoServicesForSubscription(): array
    {
        return [
            'hnk_framework.seo_manager' => '?'.SeoManager::class,
        ];
    }

    /**
     * @return SeoManager
     */
    public function getSeoManager(): SeoManager
    {
        return $this->get('hnk_framework.seo_manager');
    }

    /**
     * @param string $title
     * @param string $description
     * @param string $keywords
     * @param string $subTitle
     * @return SeoPage
     */
    public function setHeader($title, $description = '', $keywords = '', $subTitle = ''): void
    {
        $this->getSeoManager()->getSeoPage()
            ->setTitle($title)
            ->setDescription($description)
            ->setKeywords($keywords)
            ->setSubTitle($subTitle);
    }

    /**
     * @param string $name
     * @param string $url
     * @return SeoPage
     */
    public function addBreadcrumb($name, $url = ''): SeoPage
    {
        return $this->getSeoManager()->getSeoPage()
            ->addBreadcrumb($name, $url);
    }
}
