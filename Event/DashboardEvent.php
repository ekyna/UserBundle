<?php

namespace Ekyna\Bundle\UserBundle\Event;

use Ekyna\Bundle\UserBundle\Model;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class DashboardEvent
 * @package Ekyna\Bundle\UserBundle\Event
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class DashboardEvent extends Event
{
    const DASHBOARD = 'ekyna_user.account.dashboard';

    /**
     * @var Model\UserInterface
     */
    private $user;

    /**
     * @var Model\DashboardWidget[]
     */
    private $widgets;

    /**
     * @var bool
     */
    private $sorted;


    /**
     * Constructor.
     *
     * @param Model\UserInterface $user
     */
    public function __construct(Model\UserInterface $user)
    {
        $this->user = $user;
        $this->widgets = [];
        $this->sorted = false;
    }

    /**
     * Returns the user.
     *
     * @return Model\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Adds the dashboard widget.
     *
     * @param Model\DashboardWidget $widget
     *
     * @return $this
     */
    public function addWidget(Model\DashboardWidget $widget)
    {
        $this->widgets[] = $widget;
        $this->sorted = false;

        return $this;
    }

    /**
     * Returns the widgets.
     *
     * @return Model\DashboardWidget[]
     */
    public function getWidgets()
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
