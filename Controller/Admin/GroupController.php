<?php

namespace Ekyna\Bundle\UserBundle\Controller\Admin;

use Ekyna\Bundle\AdminBundle\Controller\Resource\SortableTrait;
use Ekyna\Bundle\AdminBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GroupController
 * @package Ekyna\Bundle\UserBundle\Controller\Admin
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class GroupController extends ResourceController
{
    use SortableTrait;

    /**
     * {@inheritdoc}
     */
    public function showAction(Request $request)
    {
        $context = $this->loadContext($request);

        /** @var \Ekyna\Bundle\UserBundle\Model\GroupInterface $resource */
        $resource = $context->getResource();

        $this->isGranted('VIEW', $resource);

        $datas = array(
            'acl_datas' => $this->get('ekyna_admin.acl_operator')->generateGroupViewDatas($resource)
        );

        return $this->render(
            $this->config->getTemplate('show.html'),
            $context->getTemplateVars($datas)
        );
    }

    /**
     * Edit permissions action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editPermissionsAction(Request $request)
    {
        $context = $this->loadContext($request);

        /** @var \Ekyna\Bundle\UserBundle\Model\GroupInterface $resource */
        $resource = $context->getResource();

        $this->isGranted('EDIT', $resource);

        $cancelPath = $this->generateResourcePath($resource);

        $aclOperator = $this->get('ekyna_admin.acl_operator');
        $builder = $this->createFormBuilder(
            array('acls' => $aclOperator->generateGroupFormDatas($resource)),
            array('admin_mode' => true, '_redirect_enabled' => true)
        );
        $aclOperator->buildGroupForm($builder);

        $form = $builder->getForm();
        $form->add('actions', 'form_actions', [
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
        ]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $aclOperator->updateGroup($resource, $form->get('acls')->getData());

                $this->addFlash('Les permissions ont bien été modifiées.', 'success');

                if (null !== $redirectPath = $form->get('_redirect')->getData()) {
                    return $this->redirect($redirectPath);
                }

                return $this->redirect($cancelPath );
            } catch(\Exception $e) {
                $this->addFlash('Erreur lors de la mise à jour des permissions :<br>' . $e->getMessage(), 'danger');
            }
        }

        $this->appendBreadcrumb(
            sprintf('%s-edit-permissions', $this->config->getResourceName()),
            'ekyna_user.group.button.edit_permissions'
        );

        $datas = array(
            'permissions' => $aclOperator->getPermissions(),
            'form' => $form->createView(),
        );

        return $this->render(
            'EkynaUserBundle:Admin/Group:edit_permissions.html.twig',
            $context->getTemplateVars($datas)
        );
    }
}
