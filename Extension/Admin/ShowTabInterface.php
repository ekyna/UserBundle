<?php

namespace Ekyna\Bundle\UserBundle\Extension\Admin;

/**
 * Interface ShowTabInterface
 * @package Ekyna\Bundle\UserBundle\Extension\Admin
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface ShowTabInterface
{
    /**
     * Returns the label.
     *
     * @return string
     */
    public function getLabel();

    /**
     * Returns the data.
     *
     * @return array
     */
    public function getData();

    /**
     * Returns the template.
     *
     * @return string
     */
    public function getTemplate();

    /**
     * Returns the extension position.
     *
     * @return integer
     */
    public function getPosition();
}
