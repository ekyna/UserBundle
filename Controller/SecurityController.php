<?php

namespace Ekyna\Bundle\UserBundle\Controller;

use Ekyna\Bundle\CoreBundle\Controller\Controller;
use Ekyna\Bundle\CoreBundle\Modal\Modal;
use Ekyna\Bundle\UserBundle\Form\Type\LoginType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SecurityController
 * @package Ekyna\Bundle\UserBundle\Controller
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class SecurityController extends Controller
{
    /**
     * Login action.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function loginAction(Request $request)
    {
        $form = $this->createLoginForm();

        if ($request->isXmlHttpRequest()) {
            $modal = $this->createLoginModal($form);

            return $this->get('ekyna_core.modal')->render($modal);
        }

        return $this
            ->render('EkynaUserBundle:Security:login.html.twig', [
                'form' => $form->createView(),
            ])
            ->setPrivate();
    }

    /**
     * Creates the login form.
     *
     * @return FormInterface
     */
    protected function createLoginForm()
    {
        $form = $this->get('form.factory')->createNamed('', LoginType::class);

        $authenticationUtils = $this->get('security.authentication_utils');

        // Authentication error
        if ($error = $authenticationUtils->getLastAuthenticationError()) {
            $form->addError(new FormError($error->getMessage()));
        }

        // Last username
        if ($lastUserName = $authenticationUtils->getLastUsername()) {
            $form->setData(['_username' => $lastUserName]);
        }

        return $form;
    }

    /**
     * Creates the login modal.
     *
     * @param FormInterface $form
     *
     * @return Modal
     */
    protected function createLoginModal(FormInterface $form)
    {
        $modal = new Modal('ekyna_user.account.login.title');
        $modal
            ->setSize(Modal::SIZE_SMALL)
            ->setContent($form->createView())
            ->setVars([
                'form_template' => 'EkynaUserBundle:Security:modal_login_form.html.twig',
            ])
            ->setButtons([
                'submit' => [
                    'id'       => 'submit',
                    'label'    => 'ekyna_core.button.login',
                    'icon'     => 'glyphicon glyphicon-ok',
                    'cssClass' => 'btn-primary',
                    'autospin' => true,
                ],
                'close' => [
                    'id'       => 'close',
                    'label'    => 'ekyna_core.button.close',
                    'icon'     => 'glyphicon glyphicon-remove',
                    'cssClass' => 'btn-default',
                ]
            ]);

        return $modal;
    }
}
