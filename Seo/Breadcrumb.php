<?php


namespace Hnk\HnkFrameworkBundle\Seo;


class Breadcrumb implements BreadcrumbInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $route;

    /**
     * @var array
     */
    protected $routeParameters;

    /**
     * @param string $name
     * @param string $route
     * @param array $routeParameters
     */
    public function __construct($name, $route = null, array $routeParameters = [])
    {
        $this->name = $name;
        $this->route = $route;
        $this->routeParameters = $routeParameters;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute($route): void
    {
        $this->route = $route;
    }

    /**
     * @inheritDoc
     */
    public function getRouteParameters(): array
    {
        return $this->routeParameters;
    }

    /**
     * @param array $routeParameters
     */
    public function setRouteParameters(array $routeParameters)
    {
        $this->routeParameters = $routeParameters;
    }
}