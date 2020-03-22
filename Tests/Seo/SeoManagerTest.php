<?php

namespace Hnk\HnkFrameworkBundle\Tests\Seo;

use Hnk\HnkFrameworkBundle\Seo\SeoManager;
use PHPUnit\Framework\TestCase;

class SeoManagerTest extends TestCase
{
    public function testSeoManager()
    {
        $manager = new SeoManager("title");

        $page = $manager->getSeoPage();

        $this->assertEquals("title", $page->getTitle());
        $this->assertEmpty($page->getDescription());
        $this->assertEmpty($page->getKeywords());
        $this->assertEmpty($page->getSubTitle());
        $this->assertEmpty($page->getBreadcrumbs());
    }
}
