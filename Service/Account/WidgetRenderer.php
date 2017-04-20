<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Service\Account;

use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Twig\Environment;

/**
 * Class WidgetRenderer
 * @package Ekyna\Bundle\UserBundle\Service\Account
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class WidgetRenderer
{
    private Environment $twig;


    /**
     * Constructor.
     *
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Renders the XHR response content.
     *
     * @param UserInterface|null $user
     * @param string|null        $redirect
     *
     * @return string
     */
    public function render(UserInterface $user = null, string $redirect = null): string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->twig->render('@EkynaUser/widget.xml.twig', [
            'user'     => $user,
            'redirect' => $redirect,
        ]);
    }
}
