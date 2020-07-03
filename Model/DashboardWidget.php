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
     * @var bool
     */
    private $panel;


    /**
     * Constructor.
     *
     * @param string $title
     * @param string $template
     * @param string $theme
     */
    public function __construct(string $title, string $template, string $theme = 'default')
    {
        $this->title = $title;
        $this->template = $template;
        $this->theme = $theme;
        $this->parameters = [];
        $this->priority = 0;
        $this->buttons = [];
        $this->panel = true;
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
     * @return DashboardWidget
     */
    public function setTitle(string $title): DashboardWidget
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Returns the template.
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * Sets the template.
     *
     * @param string $template
     *
     * @return DashboardWidget
     */
    public function setTemplate(string $template): DashboardWidget
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Returns the theme.
     *
     * @return string
     */
    public function getTheme(): string
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
    public function setTheme(string $theme): DashboardWidget
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Returns the parameters.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Sets the parameters.
     *
     * @param array $parameters
     *
     * @return DashboardWidget
     */
    public function setParameters(array $parameters): DashboardWidget
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Returns the priority.
     *
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Sets the priority.
     *
     * @param int $priority
     *
     * @return DashboardWidget
     */
    public function setPriority(int $priority): DashboardWidget
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Returns the buttons.
     *
     * @return DashboardWidgetButton[]
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }

    /**
     * Returns the buttons.
     *
     * @param DashboardWidgetButton $button
     *
     * @return DashboardWidget
     */
    public function addButton(DashboardWidgetButton $button): DashboardWidget
    {
        $this->buttons[] = $button;

        return $this;
    }

    /**
     * Returns the panel.
     *
     * @return bool
     */
    public function isPanel(): bool
    {
        return $this->panel;
    }

    /**
     * Sets the panel.
     *
     * @param bool $panel
     *
     * @return DashboardWidget
     */
    public function setPanel(bool $panel): DashboardWidget
    {
        $this->panel = $panel;

        return $this;
    }
}
