<?php

namespace Ekyna\Bundle\UserBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Ekyna\Bundle\UserBundle\Repository\GroupRepository;
use Ekyna\Bundle\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Doctrine\UserManager as BaseManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;

/**
 * Class UserManager
 * @package Ekyna\Bundle\UserBundle\Doctrine
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserManager extends BaseManager implements UserManagerInterface
{
    /**
     * @var GroupRepository
     */
    protected $groupRepository;


    /**
     * Constructor.
     *
     * @param PasswordUpdaterInterface $passwordUpdater
     * @param CanonicalFieldsUpdater   $canonicalFieldsUpdater
     * @param ObjectManager            $om
     * @param GroupRepository          $groupRepository
     * @param string                   $userClass
     */
    public function __construct(
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdater $canonicalFieldsUpdater,
        ObjectManager $om,
        GroupRepository $groupRepository,
        $userClass
    ) {
        parent::__construct($passwordUpdater, $canonicalFieldsUpdater, $om, $userClass);

        $this->groupRepository = $groupRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function createUser()
    {
        $class = $this->getClass();

        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $user = new $class;
        $user->setGroup($this->groupRepository->findDefault());

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function updateUser(UserInterface $user, $andFlush = true)
    {
        $this->updateUsername($user);

        parent::updateUser($user, $andFlush);
    }

    /**
     * Copy email to username if empty.
     *
     * @param UserInterface $user
     */
    public function updateUsername(UserInterface $user)
    {
        if (0 == strlen($user->getUsername())) {
            $user->setUsername($user->getEmail());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function generatePassword(UserInterface $user)
    {
        $password = bin2hex(random_bytes(4));
        $user->setPlainPassword($password);
    }
}
