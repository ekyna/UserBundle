<?php

namespace Ekyna\Bundle\UserBundle\Model;

/**
 * Class DashboardWidgetButton
 * @package Ekyna\Bundle\UserBundle\Model
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class DashboardWidgetButton
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var string
     */
    private $theme;


    /**
     * Constructor.
     *
     * @param string $title
     * @param string $route
     * @param array  $parameters
     * @param string $theme
     */
    public function __construct($title, $route, array $parameters = [], $theme = 'default')
    {
        $this->title = $title;
        $this->route = $route;
        $this->parameters = $parameters;
        $this->theme = $theme;
    }

    /**
     * Returns the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the route.
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Returns the parameters.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Returns the theme.
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }
}
