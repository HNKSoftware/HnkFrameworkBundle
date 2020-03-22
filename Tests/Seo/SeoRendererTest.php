<?php

namespace Hnk\HnkFrameworkBundle\Tests\Seo;

use Hnk\HnkFrameworkBundle\Seo\SeoManager;
use Hnk\HnkFrameworkBundle\Seo\SeoRenderer;
use PHPUnit\Framework\TestCase;

class SeoRendererTest extends TestCase
{
    private const TITLE = "Page title";
    private const DESCRIPTION = "Page description";
    private const KEYWORDS = "Page keywords";

    public function testRenderSeo()
    {
        $rendererWithAllProperties = $this->prepareSeoRenderer();
        $this->assertEquals(
            sprintf(
                '<title>%s</title><meta name="description" content="%s"><meta name="keywords" content="%s">',
                self::TITLE, self::DESCRIPTION, self::KEYWORDS
            ),
            $rendererWithAllProperties->renderSeo()
        );

        $rendererWithOnlyTile = $this->prepareSeoRenderer(self::TITLE, null, null);
        $this->assertEquals(
            sprintf('<title>%s</title>', self::TITLE),
            $rendererWithOnlyTile->renderSeo()
        );

        $rendererWithTileAndDescription = $this->prepareSeoRenderer(self::TITLE, self::DESCRIPTION, null);
        $this->assertEquals(
            sprintf('<title>%s</title><meta name="description" content="%s">', self::TITLE, self::DESCRIPTION),
            $rendererWithTileAndDescription->renderSeo()
        );

        $rendererWithTileAndKeywords = $this->prepareSeoRenderer(self::TITLE, null, self::KEYWORDS);
        $this->assertEquals(
            sprintf('<title>%s</title><meta name="keywords" content="%s">', self::TITLE, self::KEYWORDS),
            $rendererWithTileAndKeywords->renderSeo()
        );
    }

    public function testRenderSeoTitle()
    {
        $this->assertEquals(
            sprintf("<title>%s</title>", self::TITLE),
            $this->prepareSeoRenderer()->renderSeoTitle()
        );
    }

    public function testRenderSeoKeywords()
    {
        $this->assertEquals(
            sprintf('<meta name="keywords" content="%s">', self::KEYWORDS),
            $this->prepareSeoRenderer()->renderSeoKeywords()
        );
    }

    public function testRenderSeoDescription()
    {
        $this->assertEquals(
            sprintf('<meta name="description" content="%s">', self::DESCRIPTION),
            $this->prepareSeoRenderer()->renderSeoDescription()
        );
    }

    private function prepareSeoRenderer($title = self::TITLE, $description = self::DESCRIPTION, $keywords = self::KEYWORDS): SeoRenderer
    {
        $manager = new SeoManager($title);
        if ($description) {
            $manager->getSeoPage()->setDescription($description);
        }
        if ($keywords) {
            $manager->getSeoPage()->setKeywords(self::KEYWORDS);
        }

        return new SeoRenderer($manager);
    }
}
