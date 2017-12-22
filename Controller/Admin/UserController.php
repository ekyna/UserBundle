<?php

namespace Ekyna\Bundle\UserBundle\Controller\Admin;

use Ekyna\Bundle\AdminBundle\Controller\Context;
use Ekyna\Bundle\AdminBundle\Controller\Resource\ToggleableTrait;
use Ekyna\Bundle\AdminBundle\Controller\ResourceController;
use Ekyna\Bundle\UserBundle\Event\UserEvents;
use Ekyna\Bundle\UserBundle\Service\Search\UserRepository;
use Ekyna\Component\Resource\Event\ResourceMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class UserController
 * @package Ekyna\Bundle\UserBundle\Controller\Admin
 * @author  Étienne Dauvergne <contact@ekyna.com>
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
    public function searchAction(Request $request)
    {
        //$callback = $request->query->get('callback');
        $limit = intval($request->query->get('limit'));
        $query = trim($request->query->get('query'));
        $roles = $request->query->get('roles');

        $repository = $this->get('fos_elastica.manager')->getRepository($this->config->getResourceClass());
        if (!$repository instanceOf UserRepository) {
            throw new \RuntimeException('Expected instance of ' . UserRepository::class);
        }

        if (!empty($roles)) {
            $groups = $this->get('ekyna_user.group.repository')->findByRoles($roles);

            $results = $repository->searchByGroups($query, $groups, $limit);
        } else {
            $results = $repository->defaultSearch($query, $limit);
        }

        $data = $this->container->get('serializer')->serialize([
            'results'     => $results,
            'total_count' => count($results),
        ], 'json', ['groups' => ['Default']]);

        $response = new Response($data);
        $response->headers->set('Content-Type', 'text/javascript');

        return $response;
    }

    /**
     * Generates a new password for the user.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function generatePasswordAction(Request $request)
    {
        $context = $this->loadContext($request);
        $resourceName = $this->config->getResourceName();
        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $resource */
        $resource = $context->getResource($resourceName);

        $this->isGranted('EDIT', $resource);

        // Prevent changing password of super admin
        if (in_array('ROLE_SUPER_ADMIN', $resource->getGroup()->getRoles())) {
            throw new AccessDeniedHttpException();
        }

        $redirect = $this->generateResourcePath($resource);

        $operator = $this->getOperator();
        $event = $operator->createResourceEvent($resource);

        $dispatcher = $this->get('ekyna_resource.event_dispatcher');

        // Pre generate event
        $dispatcher->dispatch(UserEvents::PRE_GENERATE_PASSWORD, $event);
        if ($event->isPropagationStopped()) {
            $event->toFlashes($this->getFlashBag());

            return $this->redirect($redirect);
        }

        // New password
        $this->get('fos_user.user_manager')->generatePassword($resource);
        $password = $resource->getPlainPassword();
        $event
            ->addMessage(new ResourceMessage(
                sprintf('Generated password : "%s".', $password),
                ResourceMessage::TYPE_INFO
            ))
            ->addData('password', $password);

        // TODO use ResourceManager
        // Update event
        $operator->update($event);
        if ($event->isPropagationStopped()) {
            $event->toFlashes($this->getFlashBag());

            return $this->redirect($redirect);
        }

        // Post Generate event
        $dispatcher->dispatch(UserEvents::POST_GENERATE_PASSWORD, $event);

        // Flashes
        $event->toFlashes($this->getFlashBag());

        return $this->redirect($redirect);
    }

    /**
     * Clears the user password request.
     *
     * @param Request $request
     *
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
                ->setPasswordRequestedAt(null);

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
