<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Event;

use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AccountEvent
 * @package Ekyna\Bundle\UserBundle\Event
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class AccountEvent
{
    public const REGISTRATION_INITIALIZE = 'ekyna_user.registration.initialize';
    public const REGISTRATION_COMPLETED  = 'ekyna_user.registration.completed';

    private UserInterface  $user;
    private ?FormInterface $form;
    private ?Response      $response = null;

    public function __construct(UserInterface $user, ?FormInterface $form)
    {
        $this->user = $user;
        $this->form = $form;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getForm(): ?FormInterface
    {
        return $this->form;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(Response $response): AccountEvent
    {
        $this->response = $response;

        return $this;
    }
}
