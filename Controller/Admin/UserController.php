<?php

namespace Ekyna\Bundle\UserBundle\Controller\Admin;

use Ekyna\Bundle\AdminBundle\Controller\ResourceController;
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
     * {@inheritdoc}
     */
    protected function getManager()
    {
        return $this->get('fos_user.user_manager');
    }
}
