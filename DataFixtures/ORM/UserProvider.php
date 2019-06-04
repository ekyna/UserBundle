<?php

namespace Ekyna\Bundle\UserBundle\DataFixtures\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Ekyna\Bundle\CoreBundle\DataFixtures\ORM\Fixtures;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Bundle\UserBundle\Model\UserManagerInterface;
use Ekyna\Bundle\UserBundle\Repository\UserRepositoryInterface;

/**
 * Class UserProvider
 * @package Ekyna\Bundle\UserBundle\DataFixtures\ORM
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class UserProvider
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    /**
     * Constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param UserManagerInterface    $userManager
     * @param EntityManagerInterface  $entityManager
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        UserManagerInterface $userManager,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->userManager = $userManager;
        $this->entityManager = $entityManager;
    }

    /**
     * Creates a user.
     *
     * @param string|null $email
     * @param string|null $password
     *
     * @return UserInterface
     */
    public function createUser(string $email = null, string $password = null): UserInterface
    {
        /** @var UserInterface $user */
        $user = $this->userManager->createUser();

        $user
            ->setSendCreationEmail(false)
            ->setEnabled(true)
            ->setEmail($email ?? Fixtures::getFaker()->email)
            ->setPlainPassword($password ?? 'password');

        $this->userManager->updateUser($user, false);

        return $user;
    }
}
