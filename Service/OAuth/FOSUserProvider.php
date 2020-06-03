<?php

namespace Ekyna\Bundle\UserBundle\Service\OAuth;

use Ekyna\Bundle\UserBundle\Entity\User;
use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class FOSUserProvider
 * @package Ekyna\Bundle\UserBundle\Service\OAuth
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FOSUserProvider extends FOSUBUserProvider
{
    /**
     * @var TokenManager
     */
    protected $tokenManager;


    /**
     * Constructor
     *
     * @param UserManagerInterface $userManager ,
     * @param TokenManager         $manager
     */
    public function __construct(
        UserManagerInterface $userManager,
        TokenManager $manager
    ) {
        parent::__construct($userManager, ['identifier' => 'id']);

        $this->tokenManager = $manager;
    }

    /**
     * @inheritDoc
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $identifier = $response->getUsername();
        $owner = $response->getResourceOwner()->getName();

        $token = $this->tokenManager->findByIdentifier($owner, $identifier);

        if (null === $token) {
            $email = $response->getEmail();

            // Create user if not found
            if (null === $user = $this->userManager->findUserByEmail($email)) {
                // TODO Redirect to registration page.
                //throw new AccountNotLinkedException();

                /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
                $user = $this->userManager->createUser();
                $user
                    ->setUsername($response->getRealName())
                    ->setEmail($email)
                    ->setEnabled(true);

                $password = bin2hex(random_bytes(4));
                $user->setPlainPassword($password);

                $this->userManager->updateUser($user);
            }
        } else {
            $user = $token->getUser();
        }

        $this->tokenManager->update($user, $response);

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Expected an instance of %s, but got "%s".', User::class, get_class($user)));
        }

        // TODO logout previous user

        $this->tokenManager->update($user, $response);
    }

    /**
     * @inheritDoc
     */
    public function disconnect(UserInterface $user, UserResponseInterface $response)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Expected an instance of %s, but got "%s".', User::class, get_class($user)));
        }

        $this->tokenManager->logout($user, $response);
    }

    /**
     * @inheritDoc
     */
    protected function getProperty(UserResponseInterface $response)
    {
        throw new \LogicException("No longer used.");
    }
}
