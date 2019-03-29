<?php

namespace Ekyna\Bundle\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class AuthenticationEvent
 * @package Ekyna\Bundle\UserBundle\Event
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class AuthenticationEvent extends Event
{
    const SUCCESS = 'ekyna_user.authentication.success';
    const FAILURE = 'ekyna_user.authentication.failure';

    /**
     * @var Request
     */
    private $request;

    /**
     * @var TokenInterface
     */
    private $token;

    /**
     * @var AuthenticationException
     */
    private $exception;

    /**
     * @var Response
     */
    private $response;


    /**
     * Constructor.
     *
     * @param Request                 $request
     * @param TokenInterface          $token
     * @param AuthenticationException $exception
     */
    public function __construct(
        Request $request,
        TokenInterface $token = null,
        AuthenticationException $exception = null
    ) {
        $this->request = $request;
        $this->token = $token;
        $this->exception = $exception;
    }

    /**
     * Returns the request.
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Returns the token.
     *
     * @return TokenInterface
     */
    public function getToken(): ?TokenInterface
    {
        return $this->token;
    }

    /**
     * Returns the exception.
     *
     * @return AuthenticationException
     */
    public function getException(): ?AuthenticationException
    {
        return $this->exception;
    }

    /**
     * Returns the response.
     *
     * @return Response
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * Sets the response.
     *
     * @param Response $response
     */
    public function setResponse(?Response $response)
    {
        $this->response = $response;
    }
}
