<?php

namespace Ekyna\Bundle\UserBundle\Controller;

use Ekyna\Bundle\CoreBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AccountController
 * @package Ekyna\Bundle\UserBundle\Controller
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AccountController extends Controller
{
    /**
     * Home action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function homeAction(Request $request)
    {
        $securityContext = $this->get('security.context');
        if (!(null !== $securityContext->getToken() && $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED'))) {
            $request->getSession()->set('_ekyna.login_success.target_path', 'fos_user_profile_show');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }

    /**
     * Login widget action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginWidgetAction()
    {
        return $this
            ->render('EkynaUserBundle:Security:login_widget.html.twig')
            ->setPrivate()
        ;
    }
}
