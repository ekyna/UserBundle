<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Controller;

use Ekyna\Bundle\UiBundle\Model\Modal;
use Ekyna\Bundle\UiBundle\Service\Modal\ModalRenderer;
use Ekyna\Bundle\UserBundle\Manager\TokenManager;
use Ekyna\Component\User\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

/**
 * Class SecurityController
 * @package Ekyna\Bundle\UserBundle\Controller
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class SecurityController extends BaseController
{
    protected ModalRenderer         $modalRenderer;
    protected TokenManager          $loginTokenManager;
    protected UrlGeneratorInterface $urlGenerator;

    public function __construct(
        ModalRenderer         $modalRenderer,
        TokenManager          $loginTokenManager,
        UrlGeneratorInterface $urlGenerator,
        AuthenticationUtils   $authenticationUtils,
        Environment           $twig,
        array                 $config
    ) {
        parent::__construct($authenticationUtils, $twig, $config);

        $this->modalRenderer = $modalRenderer;
        $this->loginTokenManager = $loginTokenManager;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Form login action.
     */
    public function login(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $modal = $this->createLoginModal();

            return $this->modalRenderer->render($modal);
        }

        return parent::login($request);
    }

    protected function createLoginModal(): Modal
    {
        $modal = new Modal('account.login.title');
        $modal
            ->setDomain('EkynaUser')
            ->setSize(Modal::SIZE_SMALL)
            ->setTemplate(
                '@EkynaUser/Security/modal_login_form.html.twig',
                $this->getLoginParameters()
            )
            ->setButtons([
                array_replace(Modal::BTN_SUBMIT, [
                    'label' => 'button.login',
                ]),
                Modal::BTN_CLOSE,
            ]);

        return $modal;
    }
}
