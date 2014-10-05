<?php

namespace Ekyna\Bundle\UserBundle\Controller;

use Ekyna\Bundle\CoreBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class AddressController
 * @package Ekyna\Bundle\UserBundle\Controller
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AddressController extends Controller
{
    /**
     * List address action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $user = $this->getUser();
        $repository = $this->get('ekyna_user.address.repository');

        $addresses = $repository->findByUser($user);

        return $this->render('EkynaUserBundle:Address:list.html.twig', array(
            'addresses' => $addresses
        ));
    }

    /**
     * New address action.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $user = $this->getUser();
        $repository = $this->get('ekyna_user.address.repository');

        $address = $repository->createNew($user);
        $address->setUser($user);

        $form = $this->createForm('ekyna_user_address', $address, array(
            '_redirect_enabled' => true,
            '_footer' => array(
                'cancel_path' => $this->generateUrl('ekyna_user_address_list'),
            ),
        ));

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();

            $this->addFlash('L\'adresse a été créée avec succès.', 'success');

            if (null !== $redirectPath = $form->get('_redirect')->getData()) {
                return $this->redirect($redirectPath);
            }

            return $this->redirect($this->generateUrl('ekyna_user_address_list'));
        }

        return $this->render('EkynaUserBundle:Address:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Edit address action.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        $repository = $this->get('ekyna_user.address.repository');

        if (null === $address = $repository->find($request->attributes->get('addressId'))) {
            throw new NotFoundHttpException('Adresse introuvable.');
        }
        if ($address->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException('Vous n\'avez pas l\'autorisation pour acceder à cette resource.');
        }

        $form = $this->createForm('ekyna_user_address', $address, array(
            '_redirect_enabled' => true,
            '_footer' => array(
                'cancel_path' => $this->generateUrl('ekyna_user_address_list'),
            ),
        ));

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();

            $this->addFlash('L\'adresse a été modifiée avec succès.', 'success');

            if (null !== $redirectPath = $form->get('_redirect')->getData()) {
                return $this->redirect($redirectPath);
            }

            return $this->redirect($this->generateUrl('ekyna_user_address_list'));
        }

        return $this->render('EkynaUserBundle:Address:edit.html.twig', array(
            'address' => $address,
            'form' => $form->createView()
        ));
    }

    /**
     * Remove address action.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function removeAction(Request $request)
    {
        $user = $this->getUser();
        $repository = $this->get('ekyna_user.address.repository');

        if (null === $address = $repository->find($request->attributes->get('addressId'))) {
            throw new NotFoundHttpException('Adresse introuvable.');
        }
        if ($address->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException('Vous n\'avez pas l\'autorisation pour acceder à cette resource.');
        }

        $form = $this->createFormBuilder(null, array(
                '_redirect_enabled' => true,
                '_footer' => array(
                    'cancel_path' => $this->generateUrl('ekyna_user_address_list'),
                ),
            ))
            ->add('confirm', 'checkbox', array(
                'label' => 'Souhaitez-vous réellement supprimer cette adresse ?',
                'attr' => array('align_with_widget' => true),
                'required' => true
            ))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($address);
            $em->flush();

            $this->addFlash('L\'addresse a été supprimée avec succès.', 'success');

            if (null !== $redirectPath = $form->get('_redirect')->getData()) {
                return $this->redirect($redirectPath);
            }

            return $this->redirect($this->generateUrl('ekyna_user_address_list'));
        }

        return $this->render('EkynaUserBundle:Address:remove.html.twig', array(
            'address' => $address,
            'form' => $form->createView()
        ));
    }
}
