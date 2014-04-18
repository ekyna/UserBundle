<?php

namespace Ekyna\Bundle\UserBundle\Controller\Admin;

use Ekyna\Bundle\AdminBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;

/**
 * GroupController
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class GroupController extends ResourceController
{
    public function showAction(Request $request)
    {
        $context = $this->loadContext($request);
        $resourceName = $this->config->getResourceName();
        $resource = $context->getResource($resourceName);

        $this->isGranted('VIEW', $resource);

        $aclDatas = $this->get('ekyna_admin.acl_manipulator')->generateGroupViewDatas($resource);

        return $this->render(
            $this->config->getTemplate('show.html'),
            $context->getTemplateVars(array('acl_datas' => $aclDatas))
        );
    }

    public function editPermissionsAction(Request $request)
    {
        $context = $this->loadContext($request);
        $resourceName = $this->config->getResourceName();
        $resource = $context->getResource($resourceName);
        
        $this->isGranted('EDIT', $resource);

        $aclManipulator = $this->get('ekyna_admin.acl_manipulator');
        $form = $aclManipulator->createGroupForm($resource);

        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $aclManipulator->updateGroup($resource, $form->getData());

                $this->addFlash('Les permissions ont bien été modifiées.', 'success');

                return $this->redirect(
                    $this->generateUrl(
                        $this->config->getRoute('show'),
                        $context->getIdentifiers(true)
                    )
                );
            } catch(\Exception $e) {
                $this->addFlash('Erreur lors de la mise à jour des permissions :<br>' . $e->getMessage(), 'error');
            }
        }

        $this->appendBreadcrumb(
            sprintf('%s-edit-permissions', $resourceName),
            'ekyna_user.group.button.edit_permissions'
        );

        return $this->render(
            $this->config->getTemplate('edit_permissions.html'),
            $context->getTemplateVars(array(
                'form' => $form->createView()
            ))
        );
    }
}
