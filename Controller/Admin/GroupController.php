<?php

namespace Ekyna\Bundle\UserBundle\Controller\Admin;

use Braincrafted\Bundle\BootstrapBundle\Form\Type\FormActionsType;
use Ekyna\Bundle\AdminBundle\Controller\Resource\SortableTrait;
use Ekyna\Bundle\AdminBundle\Controller\ResourceController;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GroupController
 * @package Ekyna\Bundle\UserBundle\Controller\Admin
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class GroupController extends ResourceController
{
    use SortableTrait;
}
