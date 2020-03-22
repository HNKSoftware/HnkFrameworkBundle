<?php


namespace Hnk\HnkFrameworkBundle\Seo;


interface BreadcrumbInterface
{
    /**
     * Returns name of the breadcrumb link
     * @return string
     */
    public function getName(): string;

    /**
     * Returns route name
     *
     * @return string
     */
    public function getRoute();

    /**
     * Returns route parameters
     *
     * @return array
     */
    public function getRouteParameters();
}