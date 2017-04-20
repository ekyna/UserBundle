<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Controller;

use Ekyna\Bundle\UserBundle\Event\DashboardEvent;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Bundle\UserBundle\Service\Account\WidgetRenderer;
use Ekyna\Component\User\Service\UserProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

/**
 * Class AccountController
 * @package Ekyna\Bundle\UserBundle\Controller
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class AccountController
{
    private Environment $twig;
    private UrlGeneratorInterface $urlGenerator;
    private EventDispatcherInterface $eventDispatcher;
    private WidgetRenderer $widgetRenderer;
    private UserProvider $userProvider;


    /**
     * Constructor.
     *
     * @param Environment              $twig
     * @param UrlGeneratorInterface    $urlGenerator
     * @param EventDispatcherInterface $eventDispatcher
     * @param WidgetRenderer           $widgetRenderer
     * @param UserProvider             $userProvider
     */
    public function __construct(
        Environment $twig,
        UrlGeneratorInterface $urlGenerator,
        EventDispatcherInterface $eventDispatcher,
        WidgetRenderer $widgetRenderer,
        UserProvider $userProvider
    ) {
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
        $this->eventDispatcher = $eventDispatcher;
        $this->widgetRenderer = $widgetRenderer;
        $this->userProvider = $userProvider;
    }

    /**
     * Account index action.
     *
     * @return Response
     */
    public function index(): Response
    {
        if (!$this->getUser()) {
            throw new AccessDeniedException();
        }

        $event = new DashboardEvent($this->getUser());

        $this->eventDispatcher->dispatch($event);

        /** @noinspection PhpUnhandledExceptionInspection */
        $content = $this->twig->render('@EkynaUser/Account/index.html.twig', [
            'widgets' => $event->getWidgets(),
        ]);

        $response = new Response($content);

        return $response->setPrivate();
    }

    /**
     * Account widget action.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function widget(Request $request): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new RedirectResponse(
                $this->urlGenerator->generate('ekyna_user_account_index')
            );
        }

        $content = $this->widgetRenderer->render($this->getUser());

        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/xml');

        return $response->setPrivate();
    }

    /**
     * @return UserInterface|null
     */
    protected function getUser(): ?UserInterface
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->userProvider->getUser();
    }
}
