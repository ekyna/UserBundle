<?php

namespace Ekyna\Bundle\UserBundle\Controller\Admin;

use Ekyna\Bundle\AdminBundle\Controller\Context;
use Ekyna\Bundle\AdminBundle\Controller\ResourceController;
use Ekyna\Bundle\AdminBundle\Event\ResourceMessage;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * UserController
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserController extends ResourceController
{
    /**
     * {@inheritdoc}
     */
    protected function createResource($resource)
    {
        $generator = new SecureRandom();
        $password = bin2hex($generator->nextBytes(4));
        $resource->setPlainPassword($password);
        $resource->setEnabled(true);

        $this->getManager()->updateUser($resource, false);

        $event = parent::createResource($resource);
        if (!$event->hasErrors()) {
            $event->addMessage(new ResourceMessage(
                sprintf('Generated password : "%s".', $password),
                ResourceMessage::TYPE_INFO
            ));
            // TODO send credentials by mail (FOS event ?).
        }

        return $event;
    }

    /**
     * {@inheritdoc}
     */
    protected function createNew(Context $context)
    {
        $user = $this->getManager()->createUser();

        if (null !== $context && $this->hasParent()) {
            $parentResourceName = $this->getParent()->getConfiguration()->getResourceName();
            $parent = $context->getResource($parentResourceName);

            try {
                $propertyAccessor = PropertyAccess::createPropertyAccessor();
                $propertyAccessor->setValue($user, $parentResourceName, $parent);
            } catch (\Exception $e) {
                throw new \RuntimeException('Failed to set resource\'s parent.');
            }
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    protected function getManager()
    {
        return $this->get('fos_user.user_manager');
    }
}
