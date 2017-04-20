<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Controller\Account;

use Ekyna\Bundle\UiBundle\Service\FlashHelper;
use Ekyna\Bundle\UserBundle\Form\Type\ProfileType;
use Ekyna\Bundle\UserBundle\Manager\UserManagerInterface;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Component\User\Service\UserProviderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig\Environment;

use function array_replace_recursive;
use function Symfony\Component\Translation\t;

/**
 * Class ProfileController
 * @package Ekyna\Bundle\UserBundle\Controller\Account
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ProfileController
{
    protected UserProviderInterface $userProvider;
    protected FormFactoryInterface  $formFactory;
    protected UrlGeneratorInterface $urlGenerator;
    protected UserManagerInterface  $userManager;
    protected FlashHelper           $flashHelper;
    protected Environment           $twig;
    protected array                 $config;

    public function __construct(
        UserProviderInterface $userProvider,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        UserManagerInterface $userManager,
        FlashHelper $flashHelper,
        Environment $twig,
        array $config
    ) {
        $this->userProvider = $userProvider;
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->userManager = $userManager;
        $this->flashHelper = $flashHelper;
        $this->twig = $twig;

        $this->config = array_replace_recursive([
            'form '    => ProfileType::class,
            'template' => [
                'index' => '@EkynaUser/Account/Profile/index.html.twig',
                'edit'  => '@EkynaUser/Account/Profile/edit.html.twig',
            ],
        ], $config);
    }

    public function index(): Response
    {
        $content = $this->twig->render($this->config['template']['index'], [
            'user' => $this->getUser(),
        ]);

        return (new Response($content))->setPrivate();
    }

    public function edit(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->formFactory->create($this->config['form'], $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $this->userManager->update($user);

            if (!$event->hasErrors()) {
                $this->flashHelper->addFlash(
                    t('account.profile.confirmed', [], 'EkynaUser'),
                    'info'
                );

                return new RedirectResponse(
                    $this->urlGenerator->generate('ekyna_user_account_profile')
                );
            }

            $this->flashHelper->fromEvent($event);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $content = $this->twig->render($this->config['template']['edit'], [
            'form' => $form->createView(),
        ]);

        return (new Response($content))->setPrivate();
    }

    protected function getUser(): UserInterface
    {
        if ($user = $this->userProvider->getUser()) {
            /** @noinspection PhpIncompatibleReturnTypeInspection */
            return $user;
        }

        throw new AccessDeniedException();
    }
}
