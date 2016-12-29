<?php

namespace Ekyna\Bundle\UserBundle\Controller;

use Ekyna\Bundle\CoreBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login', [
                'target_path' => 'ekyna_user_account_index'
            ]));
        }

        return $this->render('EkynaUserBundle::account.html.twig');
    }

    /**
     * Account widget action.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function widgetAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('ekyna_user_account_index');
        }

        $content = $this->get('ekyna_user.account.widget_renderer')->render($this->getUser());

        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/xml');

        return $response->setPrivate();
    }
}
