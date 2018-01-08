<?php

namespace Ekyna\Bundle\UserBundle\Model;

/**
 * Class DashboardWidget
 * @package Ekyna\Bundle\UserBundle\Model
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class DashboardWidget
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $template;

    /**
     * @var string
     */
    private $theme;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var int
     */
    private $priority;

    /**
     * @var DashboardWidgetButton[]
     */
    private $buttons;


    /**
     * Constructor.
     *
     * @param string $title
     * @param string $template
     * @param string $theme
     */
    public function __construct($title, $template, $theme = 'default')
    {
        $this->title = $title;
        $this->template = $template;
        $this->theme = $theme;
        $this->parameters = [];
        $this->priority = 0;
        $this->buttons = [];
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
     * Sets the title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Returns the template.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Sets the template.
     *
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
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

    /**
     * Sets the theme.
     *
     * @param string $theme
     *
     * @return DashboardWidget
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
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
     * Sets the parameters.
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Returns the priority.
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Sets the priority.
     *
     * @param int $priority
     *
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = intval($priority);

        return $this;
    }

    /**
     * Returns the buttons.
     *
     * @param DashboardWidgetButton $button
     *
     * @return $this
     */
    public function addButton(DashboardWidgetButton $button)
    {
        $this->buttons[] = $button;

        return $this;
    }

    /**
     * Returns the buttons.
     *
     * @return DashboardWidgetButton[]
     */
    public function getButtons()
    {
        return $this->buttons;
    }
}
