<?php

namespace Ekyna\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * AccountController
 */
class AccountController extends Controller
{
    public function homeAction(Request $request)
    {
        if($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') 
            || $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }
        return $this->redirect($this->generateUrl('fos_user_security_login'));
    }
}
