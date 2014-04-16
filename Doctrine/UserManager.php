<?php

namespace Ekyna\Bundle\UserBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Doctrine\UserManager as BaseManager;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * UserManager
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserManager extends BaseManager
{
    protected $groupRepository;

    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param CanonicalizerInterface  $usernameCanonicalizer
     * @param CanonicalizerInterface  $emailCanonicalizer
     * @param ObjectManager           $om
     * @param string                  $class
     * @param string                  $groupClass
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer, ObjectManager $om, $class, $groupClass)
    {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $om, $class);

        $this->groupRepository = $om->getRepository($groupClass);
    }

    /**
     * Returns an empty user instance
     *
     * @return UserInterface
     */
    public function createUser()
    {
        if(null === $defaultGroup = $this->groupRepository->findOneBy(array('default' => true))) {
            throw new \RuntimeException('Enable to find default group.');
        }

        $class = $this->getClass();
        $user = new $class;
        $user->setGroup($defaultGroup);

        return $user;
    }

    /**
     * Removes an object instance (ORM Object manager alias).
     *
     * @param object $object The object instance to remove.
     *
     * @return void
     */
    public function remove($user)
    {
        $this->objectManager->remove($user);
    }

    /**
     * Makes an instance managed and persistent (ORM Object manager alias).
     *
     * @param object $object The instance to make managed and persistent.
     *
     * @return void
     */
    public function persist($user)
    {
        $this->objectManager->persist($user);
    }

    /**
     * Flushes all changes to objects that have been queued up to now to the database (ORM Object manager alias).
     *
     * @return void
     */
    public function flush()
    {
        $this->objectManager->flush();
    }
}
