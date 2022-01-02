<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Controller\Account;

use Ekyna\Bundle\UiBundle\Service\FlashHelper;
use Ekyna\Bundle\UserBundle\Entity\Token;
use Ekyna\Bundle\UserBundle\Form\Type\CheckEmailType;
use Ekyna\Bundle\UserBundle\Form\Type\ResettingType;
use Ekyna\Bundle\UserBundle\Manager\TokenManager;
use Ekyna\Bundle\UserBundle\Manager\UserManagerInterface;
use Ekyna\Bundle\UserBundle\Repository\UserRepositoryInterface;
use Ekyna\Bundle\UserBundle\Service\Mailer\UserMailer;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Translation\TranslatableMessage;
use Twig\Environment;

use function array_replace;

/**
 * Class ResettingController
 * @package Ekyna\Bundle\UserBundle\Controller\Account
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ResettingController
{
    private FormFactoryInterface    $formFactory;
    private UrlGeneratorInterface   $urlGenerator;
    private TokenManager            $tokenManager;
    private UserRepositoryInterface $userRepository;
    private UserManagerInterface    $userManager;
    private UserMailer              $mailer;
    private FlashHelper             $flashHelper;
    private Environment             $twig;
    private array                   $config;

    public function __construct(
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        TokenManager $tokenManager,
        UserRepositoryInterface $userRepository,
        UserManagerInterface $userManager,
        UserMailer $mailer,
        FlashHelper $flashHelper,
        Environment $twig,
        array $config
    ) {
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->tokenManager = $tokenManager;
        $this->userRepository = $userRepository;
        $this->userManager = $userManager;
        $this->mailer = $mailer;
        $this->flashHelper = $flashHelper;
        $this->twig = $twig;

        $this->config = array_replace([
            'form'     => ResettingType::class,
            'template' => '@EkynaUser/Account/Resetting/reset.html.twig',
            'check'    => false,
        ], $config);
    }

    public function index(Request $request): Response
    {
        $form = $this->formFactory->create(CheckEmailType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userRepository->findOneByEmail(
                $form->get('email')->getData()
            );

            if ($user) {
                $token = $this->tokenManager->createToken(Token::TYPE_RESETTING, $user);

                $this->mailer->sendResettingCheck($token);
            }

            return new RedirectResponse(
                $this->urlGenerator->generate('ekyna_user_account_resetting_check')
            );
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $content = $this->twig->render('@EkynaUser/Account/Resetting/index.html.twig', [
            'form' => $form->createView(),
        ]);

        return (new Response($content))->setPrivate();
    }

    public function sent(): Response
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $content = $this->twig->render('@EkynaUser/Account/Resetting/sent.html.twig');

        return (new Response($content))->setPrivate();
    }

    public function reset(Request $request): Response
    {
        try {
            $token = $this->getToken($request);
        } catch (TokenNotFoundException $exception) {
            return new RedirectResponse(
                $this->urlGenerator->generate('ekyna_user_account_resetting')
            );
        }

        $user = $token->getUser();

        $form = $this->formFactory->create($this->config['form'], $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword('TriggerPersistence');

            $event = $this->userManager->update($user);

            if (!$event->hasErrors()) {
                return new RedirectResponse(
                    $this->urlGenerator->generate('ekyna_user_account_resetting_confirmed', [
                        'token' => $token->getHash(),
                    ])
                );
            }

            $this->flashHelper->fromEvent($event);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $content = $this->twig->render('@EkynaUser/Account/Resetting/reset.html.twig', [
            'form' => $form->createView(),
        ]);

        return (new Response($content))->setPrivate();
    }

    public function confirmed(Request $request): Response
    {
        try {
            $token = $this->getToken($request);
        } catch (TokenNotFoundException $exception) {
            return new RedirectResponse(
                $this->urlGenerator->generate('ekyna_user_account_resetting')
            );
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $content = $this->twig->render('@EkynaUser/Account/Resetting/confirmed.html.twig', [
            'user' => $token->getUser(),
        ]);

        return (new Response($content))->setPrivate();
    }

    /**
     * Retrieves the token.
     *
     * @param Request $request
     *
     * @return Token
     */
    private function getToken(Request $request): Token
    {
        $token = $this
            ->tokenManager
            ->findToken(
                $request->attributes->getAlnum('token'),
                Token::TYPE_RESETTING
            );

        if (!$token) {
            $message = new TranslatableMessage('account.invalid_or_expired_token', [], 'EkynaUser');

            $this->flashHelper->addFlash($message, 'warning');

            throw new TokenNotFoundException();
        }

        return $token;
    }
}
