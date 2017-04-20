<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Controller\Account;

use Ekyna\Bundle\UserBundle\Entity\Token;
use Ekyna\Bundle\UserBundle\Event\AccountEvent;
use Ekyna\Bundle\UserBundle\Factory\UserFactoryInterface;
use Ekyna\Bundle\UserBundle\Form\Type\CheckEmailType;
use Ekyna\Bundle\UserBundle\Form\Type\RegistrationType;
use Ekyna\Bundle\UserBundle\Manager\TokenManager;
use Ekyna\Bundle\UserBundle\Manager\UserManagerInterface;
use Ekyna\Bundle\UserBundle\Service\Mailer\UserMailer;
use Ekyna\Component\User\Service\UserProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

use function array_replace;

/**
 * Class RegistrationController
 * @package Ekyna\Bundle\UserBundle\Controller
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class RegistrationController
{
    protected FormFactoryInterface     $formFactory;
    protected UrlGeneratorInterface    $urlGenerator;
    protected EventDispatcherInterface $dispatcher;
    protected TokenManager             $tokenManager;
    protected UserFactoryInterface     $userFactory;
    protected UserManagerInterface     $userManager;
    protected UserProviderInterface    $userProvider;
    protected UserMailer               $mailer;
    protected Environment              $twig;
    protected array                    $config;

    public function __construct(
        FormFactoryInterface     $formFactory,
        UrlGeneratorInterface    $urlGenerator,
        EventDispatcherInterface $dispatcher,
        TokenManager             $tokenManager,
        UserFactoryInterface     $userFactory,
        UserManagerInterface     $userManager,
        UserProviderInterface    $userProvider,
        UserMailer               $mailer,
        Environment              $twig,
        array                    $config
    ) {
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->dispatcher = $dispatcher;
        $this->tokenManager = $tokenManager;
        $this->userFactory = $userFactory;
        $this->userManager = $userManager;
        $this->userProvider = $userProvider;
        $this->mailer = $mailer;
        $this->twig = $twig;

        $this->config = array_replace([
            'form'     => RegistrationType::class,
            'template' => '@EkynaUser/Account/Registration/register.html.twig',
            'check'    => false,
        ], $config);
    }

    protected function redirectIfLoggedIn(): ?Response
    {
        if (!$user = $this->userProvider->getUser()) {
            return null;
        }

        // TODO Flash message (?)

        $redirect = $this->urlGenerator->generate('ekyna_user_account_index');

        return new RedirectResponse($redirect);
    }

    public function index(): Response
    {
        if ($redirect = $this->redirectIfLoggedIn()) {
            return $redirect;
        }

        if (!$this->config['check']) {
            $token = $this->tokenManager->createToken(Token::TYPE_REGISTRATION, null);

            return new RedirectResponse(
                $this->urlGenerator->generate('ekyna_user_account_registration_register', [
                    'token' => $token->getHash(),
                ])
            );
        }

        return new RedirectResponse(
            $this->urlGenerator->generate('ekyna_user_account_registration_email')
        );
    }

    public function email(Request $request): Response
    {
        if ($redirect = $this->redirectIfLoggedIn()) {
            return $redirect;
        }

        if (!$this->config['check']) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $form = $this->formFactory->create(CheckEmailType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $token = $this->tokenManager->createToken(Token::TYPE_REGISTRATION, null, [
                'email' => $form->get('email')->getData(),
            ]);

            $this->mailer->sendRegistrationCheck($token);

            return new RedirectResponse(
                $this->urlGenerator->generate('ekyna_user_account_registration_check', [
                    'token' => $token->getHash(),
                ])
            );
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $content = $this->twig->render($this->config['template']['email'], [
            'form' => $form->createView(),
        ]);

        return (new Response($content))->setPrivate();
    }

    public function check(Request $request): Response
    {
        if (!$this->config['check']) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $token = $this
            ->tokenManager
            ->findToken(
                $request->attributes->getAlnum('token'),
                Token::TYPE_REGISTRATION,
                false
            );

        if (!$token) {
            return new Response('Invalid token', Response::HTTP_FORBIDDEN);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $content = $this->twig->render($this->config['template']['check'], [
            'email' => $token->getData()['email'],
        ]);

        return (new Response($content))->setPrivate();
    }

    public function register(Request $request): Response
    {
        if ($redirect = $this->redirectIfLoggedIn()) {
            return $redirect;
        }

        $token = $this
            ->tokenManager
            ->findToken(
                $request->attributes->getAlnum('token'),
                Token::TYPE_REGISTRATION,
                false
            );

        if (!$token) {
            return new Response('Invalid token', Response::HTTP_FORBIDDEN);
        }

        if ($token->getUser()) {
            $redirect = $this->urlGenerator->generate('ekyna_user_account_registration_confirmed', [
                'token' => $token->getHash(),
            ]);

            return new RedirectResponse($redirect);
        }

        $user = $this->userFactory->create();
        if ($email = $token->getData()['email'] ?? null) {
            $user->setEmail($email);
        }

        // Initialize event
        $event = new AccountEvent($user, null);
        $this->dispatcher->dispatch($event, AccountEvent::REGISTRATION_INITIALIZE);
        if ($response = $event->getResponse()) {
            return $response;
        }

        $form = $this->formFactory->create($this->config['form'], $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEnabled(true);
            $event = $this->userManager->create($user);

            if (!$event->hasErrors()) {
                $token->setUser($user);
                $this->tokenManager->update($token);

                // Completed event
                $event = new AccountEvent($user, null);
                $this->dispatcher->dispatch($event, AccountEvent::REGISTRATION_COMPLETED);
                if ($response = $event->getResponse()) {
                    return $response;
                }

                return new RedirectResponse(
                    $this->urlGenerator->generate('ekyna_user_account_registration_confirmed', [
                        'token' => $token->getHash(),
                    ]),
                );
            }
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $content = $this->twig->render($this->config['template']['register'], [
            'form' => $form->createView(),
        ]);

        return (new Response($content))->setPrivate();
    }

    public function confirmed(Request $request): Response
    {
        if ($redirect = $this->redirectIfLoggedIn()) {
            return $redirect;
        }

        $token = $this
            ->tokenManager
            ->findToken(
                $request->attributes->getAlnum('token'),
                Token::TYPE_REGISTRATION
            );

        if (!$token) {
            return new Response('Invalid token', Response::HTTP_FORBIDDEN);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $content = $this->twig->render($this->config['template']['confirmed'], [
            'user' => $token->getUser(),
        ]);

        return (new Response($content))->setPrivate();
    }
}
