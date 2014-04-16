<?php

namespace Ekyna\Bundle\UserBundle\Controller\Admin;

use Ekyna\Bundle\AdminBundle\Controller\Context;
use Ekyna\Bundle\AdminBundle\Controller\ResourceController;
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
    protected function persist($resource, $creation = false)
    {
        if ($creation) {
            $generator = new SecureRandom();
            $password = bin2hex($generator->nextBytes(4));
            $resource->setPlainPassword($password);
            $resource->setEnabled(true);

            $this->addFlash(sprintf('Generated password : "%s".', $password), 'info');
        }

        $this->getManager()->updateUser($resource);
    }

    /**
     * Creates a new resource
     *
     * @param Context $context
     *
     * @return \Ekyna\Bundle\UserBundle\Entity\User
     */
    protected function createNew(Context $context)
    {
        $user = $this->getManager()->createUser();

        if(null !== $context && $this->hasParent()) {
            $parentResourceName = $this->getParent()->getConfiguration()->getResourceName();
            $parent = $context->getResource($parentResourceName);

            try {
                $propertyAcessor = PropertyAccess::createPropertyAccessor();
                $propertyAcessor->setValue($user, $parentResourceName, $parent);
                //$user->{Inflector::camelize('set_'.$parentResourceName)}($parent);
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
