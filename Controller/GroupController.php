<?php

namespace Ekyna\Bundle\UserBundle\Controller;

use Ekyna\Bundle\AdminBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;

/**
 * GroupController
 */
class GroupController extends ResourceController
{
    public function showAction(Request $request)
    {
        $resource = $this->findResourceOrThrowException();

        $this->isGranted('VIEW', $resource);

        $resourceName = $this->getResourceName();
        $aclDatas = $this->get('ekyna_admin.acl_manipulator')->generateGroupViewDatas($resource);

        return $this->render(
            $this->configuration->getTemplate('show.html'),
            array(
                $resourceName => $resource,
                'acl_datas' => $aclDatas,
            )
        );
    }

    public function editPermissionsAction($groupId)
    {
        $resource = $this->findResourceOrThrowException();
        
        $this->isGranted('EDIT', $resource);

        $resourceName = $this->getResourceName();
        $aclManipulator = $this->get('ekyna_admin.acl_manipulator');
        $form = $aclManipulator->createGroupForm($resource);

        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            try {
                $aclManipulator->updateGroup($resource, $form->getData());
                $this->addFlash('Les permissions ont bien été modifiées.', 'success');
                return $this->redirect(
                    $this->generateUrl(
                        $this->configuration->getRoute('show'),
                        array(
                            sprintf('%sId', $resourceName) => $resource->getId()
                        )
                    )
                );
            }catch(\Exception $e) {
                $this->addFlash('Erreur lors de la mise à jour des permissions :<br>' . $e->getMessage(), 'error');
            }
        }

        return $this->render(
            $this->configuration->getTemplate('edit_permissions.html'),
            array(
                $resourceName => $resource,
                'form' => $form->createView()
            )
        );
    }
}
