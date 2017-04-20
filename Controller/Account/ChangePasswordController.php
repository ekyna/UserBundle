<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Controller\Account;

use Ekyna\Bundle\UiBundle\Service\FlashHelper;
use Ekyna\Bundle\UserBundle\Form\Type\ChangePasswordType;
use Ekyna\Bundle\UserBundle\Manager\UserManagerInterface;
use Ekyna\Component\User\Service\UserProviderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Translation\TranslatableMessage;
use Twig\Environment;

/**
 * Class ChangePasswordController
 * @package Ekyna\Bundle\UserBundle\Controller\Account
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ChangePasswordController
{
    private UserProviderInterface $userProvider;
    private FormFactoryInterface  $formFactory;
    private UrlGeneratorInterface $urlGenerator;
    private UserManagerInterface  $userManager;
    private FlashHelper           $flashHelper;
    private Environment           $twig;

    public function __construct(
        UserProviderInterface $userProvider,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        UserManagerInterface $userManager,
        FlashHelper $flashHelper,
        Environment $twig
    ) {
        $this->userProvider = $userProvider;
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->userManager = $userManager;
        $this->flashHelper = $flashHelper;
        $this->twig = $twig;
    }

    public function index(Request $request): Response
    {
        if (!$user = $this->userProvider->getUser()) {
            throw new AccessDeniedException();
        }

        $form = $this->formFactory->create(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword('TriggerPersistence');

            $event = $this->userManager->update($user);

            if (!$event->hasErrors()) {
                $this->flashHelper->addFlash(
                    new TranslatableMessage('account.change_password.confirmed', [], 'EkynaUser'),
                    'info'
                );

                return new RedirectResponse(
                    $this->urlGenerator->generate('ekyna_user_account_index')
                );
            }
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $content = $this->twig->render('@EkynaUser/Account/ChangePassword/index.html.twig', [
            'form' => $form->createView(),
        ]);

        return (new Response($content))->setPrivate();
    }
}
