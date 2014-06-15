<?php

namespace Ekyna\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * AccountController
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AccountController extends Controller
{
    public function homeAction(Request $request)
    {
        if (! $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $request->getSession()->set('_ekyna.login_success.target_path', 'fos_user_profile_show');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }
}
