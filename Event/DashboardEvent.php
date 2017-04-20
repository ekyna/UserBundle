<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Event;

use Ekyna\Bundle\UserBundle\Model;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class DashboardEvent
 * @package Ekyna\Bundle\UserBundle\Event
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * @property array<Model\DashboardWidget> $widgets
 */
final class DashboardEvent extends Event
{
    private Model\UserInterface $user;
    private array               $widgets;
    private bool                $sorted;

    public function __construct(Model\UserInterface $user)
    {
        $this->user = $user;
        $this->widgets = [];
        $this->sorted = false;
    }

    public function getUser(): Model\UserInterface
    {
        return $this->user;
    }

    public function addWidget(Model\DashboardWidget $widget): DashboardEvent
    {
        $this->widgets[] = $widget;
        $this->sorted = false;

        return $this;
    }

    /**
     * @return array<Model\DashboardWidget>
     */
    public function getWidgets(): array
    {
        if (!$this->sorted) {
            uasort($this->widgets, function (Model\DashboardWidget $a, Model\DashboardWidget $b) {
                if ($a->getPriority() === $b->getPriority()) {
                    return 0;
                }

                return $a->getPriority() > $b->getPriority() ? -1 : 1;
            });

            $this->sorted = true;
        }

        return $this->widgets;
    }
}
