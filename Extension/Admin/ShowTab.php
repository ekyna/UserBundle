<?php

namespace Ekyna\Bundle\UserBundle\Extension\Admin;

/**
 * Class ShowTab
 * @package Ekyna\Bundle\UserBundle\Extension\Admin
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ShowTab implements ShowTabInterface
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $template;

    /**
     * @var integer
     */
    private $position;


    /**
     * Constructor.
     *
     * @param string  $label
     * @param array   $data
     * @param string  $template
     * @param integer $position
     */
    public function __construct($label, array $data, $template, $position = 0)
    {
        $this->label    = $label;
        $this->data     = $data;
        $this->template = $template;
        $this->position = $position;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }
}
