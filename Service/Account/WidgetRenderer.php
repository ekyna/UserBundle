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
    private $engine;


    /**
     * Constructor.
     *
     * @param EngineInterface $engine
     */
    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Renders the XHR response content.
     *
     * @param UserInterface $user
     *
     * @return string
     */
    public function render(UserInterface $user = null)
    {
        return $this->engine->render('EkynaUserBundle::widget.xml.twig', [
            'user' => $user,
        ]);
    }
}
