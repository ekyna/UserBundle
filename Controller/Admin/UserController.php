<?php

namespace Ekyna\Bundle\UserBundle\Controller\Admin;

use Ekyna\Bundle\AdminBundle\Controller\Context;
use Ekyna\Bundle\AdminBundle\Controller\Resource\ToggleableTrait;
use Ekyna\Bundle\AdminBundle\Controller\ResourceController;
use Ekyna\Bundle\AdminBundle\Event\ResourceMessage;
use Ekyna\Bundle\UserBundle\Event\UserEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class UserController
 * @package Ekyna\Bundle\UserBundle\Controller\Admin
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserController extends ResourceController
{
    use ToggleableTrait;

    /**
     * {@inheritdoc}
     */
    protected function createNew(Context $context)
    {
        return $this->get('fos_user.user_manager')->createUser();
    }

    /**
     * {@inheritdoc}
     */
    protected function buildShowData(array &$data, Context $context)
    {
        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $user = $context->getResource();

        $data['tabs'] = $this->get('ekyna_user.extension.registry')->getShowAdminTabs($user);

        return null;
    }

    /**
     * Generates a new password for the user.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function generatePasswordAction(Request $request)
    {
        $context = $this->loadContext($request);
        $resourceName = $this->config->getResourceName();
        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $resource */
        $resource = $context->getResource($resourceName);

        $this->isGranted('EDIT', $resource);

        if (in_array('ROLE_SUPER_ADMIN', $resource->getGroup()->getRoles())) {
            throw new AccessDeniedHttpException();
        }

        $this->get('fos_user.user_manager')->generatePassword($resource);
        $password = $resource->getPlainPassword();

        $event = new UserEvent($resource);
        $event
            ->addMessage(new ResourceMessage(
                sprintf('Generated password : "%s".', $password),
                ResourceMessage::TYPE_INFO
            ))
            ->addData('password', $password)
        ;

        // TODO use ResourceManager
        $this->getOperator()->update($event);
        $event->toFlashes($this->getFlashBag());

        return $this->redirect($this->generateResourcePath($resource));
    }

    /**
     * Clears the user password request.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearPasswordRequestAction(Request $request)
    {
        $context = $this->loadContext($request);
        $resourceName = $this->config->getResourceName();
        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $resource */
        $resource = $context->getResource($resourceName);

        $this->isGranted('EDIT', $resource);

        if ($resource->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            $resource
                ->setConfirmationToken(null)
                ->setPasswordRequestedAt(null)
            ;

            // TODO use ResourceManager
            $event = $this->getOperator()->update($resource);
            $event->toFlashes($this->getFlashBag());
            $this->addFlash('ekyna_user.user.alert.password_request_cleared', 'success');
        } else {
            $this->addFlash('ekyna_user.user.alert.no_password_request', 'warning');
        }

        return $this->redirect($this->generateResourcePath($resource));
    }
}
