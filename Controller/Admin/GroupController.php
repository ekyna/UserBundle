<?php

namespace Ekyna\Bundle\UserBundle\Controller\Admin;

use Ekyna\Bundle\AdminBundle\Controller\Context;
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editPermissionsAction(Request $request)
    {
        $context = $this->loadContext($request);

        $resource = $context->getResource();

        $this->isGranted('EDIT', $resource);

        $aclOperator = $this->get('ekyna_admin.acl_operator');
        $builder = $this->createFormBuilder(
            array('acls' => $aclOperator->generateGroupFormDatas($resource)),
            array(
                'admin_mode' => true,
                '_redirect_enabled' => true,
                '_footer' => array(
                    'cancel_path' => $this->generateUrl(
                        $this->config->getRoute('show'),
                        $context->getIdentifiers(true)
                    ),
                ),
            )
        );
        $aclOperator->buildGroupForm($builder);

        $form = $builder->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $aclOperator->updateGroup($resource, $form->get('acls')->getData());

                $this->addFlash('Les permissions ont bien été modifiées.', 'success');

                if (null !== $redirectPath = $form->get('_redirect')->getData()) {
                    return $this->redirect($redirectPath);
                }

                return $this->redirect(
                    $this->generateUrl(
                        $this->config->getRoute('show'),
                        $context->getIdentifiers(true)
                    )
                );
            } catch(\Exception $e) {
                $this->addFlash('Erreur lors de la mise à jour des permissions :<br>' . $e->getMessage(), 'danger');
            }
        }

        $this->appendBreadcrumb(
            sprintf('%s-edit-permissions', $resourceName),
            'ekyna_user.group.button.edit_permissions'
        );

        $datas = array(
            'permissions' => $aclOperator->getPermissions(),
            'form' => $form->createView(),
        );

        return $this->render(
            'EkynaUserBundle:Group/Admin:edit_permissions.html.twig',
            $context->getTemplateVars($datas)
        );
    }
}
