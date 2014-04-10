<?php

namespace Ekyna\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * AddressController
 */
class AddressController extends Controller
{
    public function listAction()
    {
        $user = $this->getUser();
        $repository = $this->get('ekyna_user.address.repository');

        $addresses = $repository->findByUser($user);

        return $this->render(
        	'EkynaUserBundle:Address:list.html.twig',
            array(
        	    'addresses' => $addresses
            )
        );
    }

    public function newAction()
    {
        $user = $this->getUser();
        $repository = $this->get('ekyna_user.address.repository');

        $address = $repository->createNew($user);
        $address->setUser($user);

        $form = $this->createForm('ekyna_user_address', $address);

        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'L\'adresse a été créée avec succès.');

            return $this->redirect($this->generateUrl('ekyna_user_address_list'));
        }

        return $this->render(
            'EkynaUserBundle:Address:new.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    public function editAction($addressId)
    {
        $user = $this->getUser();
        $repository = $this->get('ekyna_user.address.repository');

        if(null === $address = $repository->find($addressId)) {
            throw new NotFoundHttpException('Adresse introuvable.');
        }
        if($address->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException('Vous n\'avez pas l\'autorisation pour acceder à cette resource.');
        }

        $form = $this->createForm('ekyna_user_address', $address);

        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'L\'adresse a été modifiée avec succès.');

            return $this->redirect($this->generateUrl('ekyna_user_address_list'));
        }

        return $this->render(
            'EkynaUserBundle:Address:edit.html.twig',
            array(
                'address' => $address,
                'form' => $form->createView()
            )
        );
    }

    public function removeAction($addressId)
    {
        $user = $this->getUser();
        $repository = $this->get('ekyna_user.address.repository');

        if(null === $address = $repository->find($addressId)) {
            throw new NotFoundHttpException('Adresse introuvable.');
        }
        if($address->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException('Vous n\'avez pas l\'autorisation pour acceder à cette resource.');
        }

        $form = $this->createFormBuilder()
            ->add('confirm', 'checkbox', array(
                'label' => 'Souhaitez-vous réellement supprimer cette adresse ?',
                'attr' => array('align_with_widget' => true),
                'required' => true
            ))
            ->getForm()
        ;

        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($address);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'L\'addresse a été supprimée avec succès.');

            return $this->redirect($this->generateUrl('ekyna_user_address_list'));
        }

        return $this->render(
            'EkynaUserBundle:Address:remove.html.twig',
            array(
                'address' => $address,
                'form' => $form->createView()
            )
        );
    }
}
