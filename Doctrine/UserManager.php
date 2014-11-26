<?php

namespace Ekyna\Bundle\UserBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Ekyna\Bundle\UserBundle\Entity\GroupRepository;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use FOS\UserBundle\Doctrine\UserManager as BaseManager;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class UserManager
 * @package Ekyna\Bundle\UserBundle\Doctrine
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserManager extends BaseManager
{
    /**
     * @var GroupRepository
     */
    protected $groupRepository;

    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param CanonicalizerInterface  $usernameCanonicalizer
     * @param CanonicalizerInterface  $emailCanonicalizer
     * @param ObjectManager           $om
     * @param string                  $userClass
     * @param string                  $groupClass
     */
    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        CanonicalizerInterface $usernameCanonicalizer,
        CanonicalizerInterface $emailCanonicalizer,
        ObjectManager $om,
        $userClass,
        $groupClass
    ) {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $om, $userClass);

        $this->groupRepository = $om->getRepository($groupClass);
    }

    /**
     * Returns an empty user instance
     *
     * @return UserInterface
     */
    public function createUser()
    {
        $class = $this->getClass();
        /** @var UserInterface $user */
        $user = new $class;

        $user->setGroup($this->groupRepository->findDefault());

        return $user;
    }
}
