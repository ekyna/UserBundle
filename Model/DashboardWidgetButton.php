<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Model;

use Symfony\Contracts\Translation\TranslatableInterface;

/**
 * Class DashboardWidgetButton
 * @package Ekyna\Bundle\UserBundle\Model
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class DashboardWidgetButton
{
    private TranslatableInterface $title;
    private string                $route;
    private array                 $parameters;
    private string                $theme;

    public function __construct(
        TranslatableInterface $title,
        string                $route,
        array                 $parameters = [],
        string                $theme = 'default'
    ) {
        $this->title = $title;
        $this->route = $route;
        $this->parameters = $parameters;
        $this->theme = $theme;
    }

    public function getTitle(): TranslatableInterface
    {
        return $this->title;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getTheme(): string
    {
        return $this->theme;
    }
}
