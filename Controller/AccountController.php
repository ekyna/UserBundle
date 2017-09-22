<?php

namespace Ekyna\Bundle\UserBundle\Controller;

use Ekyna\Bundle\CoreBundle\Controller\Controller;
use Ekyna\Bundle\UserBundle\Event\DashboardEvent;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
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
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            return $this->redirect($this->generateUrl('fos_user_security_login', [
                'target_path' => 'ekyna_user_account_index'
            ]));
        }

        $event = new DashboardEvent($this->getUser());

        $this->getDispatcher()->dispatch(DashboardEvent::DASHBOARD, $event);

        return $this->render('EkynaUserBundle:Account:index.html.twig', [
            'widgets' => $event->getWidgets(),
        ]);
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

    /**
     * @return UserInterface|null
     */
    public function getUser()
    {
        return $this->get('ekyna_user.user_provider')->getUser();
    }
}
