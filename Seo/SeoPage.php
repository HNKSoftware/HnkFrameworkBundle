<?php

namespace Hnk\HnkFrameworkBundle\Seo;

class SeoPage
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $keywords;

    /**
     * @var array
     */
    protected $breadcrumbs;

    /**
     * @var string
     */
    protected $subTitle;

    /**
     * @param string $title
     * @param string $description
     * @param string $keywords
     * @param string $subTitle
     * @param array $breadcrumbs
     */
    public function __construct(
        $title,
        $description = '',
        $keywords = '',
        $subTitle = '',
        $breadcrumbs = []
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->breadcrumbs = $breadcrumbs;
        $this->subTitle = $subTitle;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return SeoPage
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return SeoPage
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasDescription(): bool
    {
        return '' !== $this->description;
    }

    /**
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords
     * @return SeoPage
     */
    public function setKeywords(string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasKeywords(): bool
    {
        return '' !== $this->keywords;
    }

    /**
     * @return array
     */
    public function getBreadcrumbs(): array
    {
        return $this->breadcrumbs;
    }

    /**
     * @param string $name
     * @param string $url
     * @return SeoPage
     */
    public function addBreadcrumb(string $name, string $url = ''): self
    {
        $this->breadcrumbs[] = ['name' => $name, 'url' => $url];

        return $this;
    }

    /**
     * @return string
     */
    public function getSubTitle(): string
    {
        return $this->subTitle;
    }

    /**
     * @param string $subTitle
     *
     * @return $this
     */
    public function setSubTitle($subTitle): self
    {
        $this->subTitle = $subTitle;

        return $this;
    }
}
