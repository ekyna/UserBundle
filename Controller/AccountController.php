<?php

namespace Ekyna\Bundle\UserBundle\Controller;

use Ekyna\Bundle\CoreBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AccountController
 * @package Ekyna\Bundle\UserBundle\Controller
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class AccountController extends Controller
{
    /**
     * Account index action.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // TODO check if AuthenticationCredentialsNotFoundException is raised without a cookie.
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $request->getSession()->set('_ekyna.login_success.target_path', 'ekyna_user_account_index');

            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        return $this->render('EkynaUserBundle::account.html.twig');
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
            ->setPrivate();
    }
}
