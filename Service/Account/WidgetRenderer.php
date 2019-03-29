<?php

namespace Ekyna\Bundle\UserBundle\Service\Account;

use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Class WidgetRenderer
 * @package Ekyna\Bundle\UserBundle\Service\Account
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class WidgetRenderer
{
    /**
     * @var EngineInterface
     */
    private $templating;


    /**
     * Constructor.
     *
     * @param EngineInterface $templating
     */
    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * Renders the XHR response content.
     *
     * @param UserInterface $user
     * @param string        $redirect
     *
     * @return string
     */
    public function render(UserInterface $user = null, string $redirect = null)
    {
        return $this->templating->render('@EkynaUser/widget.xml.twig', [
            'user'     => $user,
            'redirect' => $redirect,
        ]);
    }
}
