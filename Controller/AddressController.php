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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $user = $this->getUser();
        $repository = $this->get('ekyna_user.address.repository');

        $address = $repository->createNew();
        $address->setUser($user);

        $cancelPath = $this->generateUrl('ekyna_user_address_list');

        /** @var \Symfony\Component\Form\FormInterface $form */
        $form = $this
            ->createForm('ekyna_user_address', $address, array(
                '_redirect_enabled' => true,
            ))
            ->add('actions', 'form_actions', [
                'buttons' => [
                    'save' => [
                        'type' => 'submit', 'options' => [
                            'button_class' => 'primary',
                            'label' => 'ekyna_core.button.save',
                            'attr' => [
                                'icon' => 'ok',
                            ],
                        ],
                    ],
                    'cancel' => [
                        'type' => 'button', 'options' => [
                            'label' => 'ekyna_core.button.cancel',
                            'button_class' => 'default',
                            'as_link' => true,
                            'attr' => [
                                'class' => 'form-cancel-btn',
                                'icon' => 'remove',
                                'href' => $cancelPath,
                            ],
                        ],
                    ],
                ],
            ])
        ;

        $form->handleRequest($request);
        if ($form->isValid()) {
            $event = $this->get('ekyna_user.address.operator')->create($address);
            if (!$event->isPropagationStopped()) {
                $this->addFlash('ekyna_user.address.message.create.success', 'success');
                if (null !== $redirectPath = $form->get('_redirect')->getData()) {
                    return $this->redirect($redirectPath);
                }
                return $this->redirect($cancelPath);
            }
            $this->addFlash('ekyna_user.address.message.create.failure', 'danger');
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        $repository = $this->get('ekyna_user.address.repository');

        if (null === $address = $repository->find($request->attributes->get('addressId'))) {
            throw new NotFoundHttpException('Address not found.');
        }
        if ($address->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException('Access denied');
        }

        $cancelPath = $this->generateUrl('ekyna_user_address_list');

        /** @var \Symfony\Component\Form\FormInterface $form */
        $form = $this
            ->createForm('ekyna_user_address', $address, array(
                '_redirect_enabled' => true,
            ))
            ->add('actions', 'form_actions', [
                'buttons' => [
                    'save' => [
                        'type' => 'submit', 'options' => [
                            'button_class' => 'primary',
                            'label' => 'ekyna_core.button.save',
                            'attr' => [
                                'icon' => 'ok',
                            ],
                        ],
                    ],
                    'cancel' => [
                        'type' => 'button', 'options' => [
                            'label' => 'ekyna_core.button.cancel',
                            'button_class' => 'default',
                            'as_link' => true,
                            'attr' => [
                                'class' => 'form-cancel-btn',
                                'icon' => 'remove',
                                'href' => $cancelPath,
                            ],
                        ],
                    ],
                ],
            ])
        ;

        $form->handleRequest($request);
        if ($form->isValid()) {
            $event = $this->get('ekyna_user.address.operator')->update($address);
            if (!$event->isPropagationStopped()) {
                $this->addFlash('ekyna_user.address.message.edit.success', 'success');
                if (null !== $redirectPath = $form->get('_redirect')->getData()) {
                    return $this->redirect($redirectPath);
                }
                return $this->redirect($cancelPath);
            }
            $this->addFlash('ekyna_user.address.message.edit.failure', 'danger');
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function removeAction(Request $request)
    {
        $user = $this->getUser();
        $repository = $this->get('ekyna_user.address.repository');

        if (null === $address = $repository->find($request->attributes->get('addressId'))) {
            throw new NotFoundHttpException('Address not found.');
        }
        if ($address->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException('Access denied.');
        }

        $cancelPath = $this->generateUrl('ekyna_user_address_list');

        $form = $this->createFormBuilder(null, array(
                '_redirect_enabled' => true,
            ))
            ->add('confirm', 'checkbox', array(
                'label' => 'ekyna_user.address.message.remove.confirm',
                'attr' => array('align_with_widget' => true),
                'required' => true
            ))
            ->add('actions', 'form_actions', [
                'buttons' => [
                    'remove' => [
                        'type' => 'submit', 'options' => [
                            'button_class' => 'danger',
                            'label' => 'ekyna_core.button.remove',
                            'attr' => [
                                'icon' => 'trash',
                            ],
                        ],
                    ],
                    'cancel' => [
                        'type' => 'button', 'options' => [
                            'label' => 'ekyna_core.button.cancel',
                            'button_class' => 'default',
                            'as_link' => true,
                            'attr' => [
                                'class' => 'form-cancel-btn',
                                'icon' => 'remove',
                                'href' => $cancelPath,
                            ],
                        ],
                    ],
                ],
            ])
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isValid()) {
            $event = $this->get('ekyna_user.address.operator')->delete($address);
            if (!$event->isPropagationStopped()) {
                $this->addFlash('ekyna_user.address.message.remove.success', 'success');
                if (null !== $redirectPath = $form->get('_redirect')->getData()) {
                    return $this->redirect($redirectPath);
                }
                return $this->redirect($cancelPath);
            }
            $this->addFlash('ekyna_user.address.message.remove.failure', 'danger');
        }

        return $this->render('EkynaUserBundle:Address:remove.html.twig', array(
            'address' => $address,
            'form' => $form->createView()
        ));
    }
}
