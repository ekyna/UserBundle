<?php

namespace Ekyna\Bundle\UserBundle\Service\OAuth;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Ekyna\Bundle\UserBundle\Entity\OAuthToken;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

/**
 * Class TokenManager
 * @package Ekyna\Bundle\UserBundle\Service\OAuth
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class TokenManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var EntityRepository
     */
    protected $tokenRepository;


    /**
     * Constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Updates (or creates) the user OAuth token.
     *
     * @param UserInterface         $user
     * @param UserResponseInterface $response
     *
     * @return OAuthToken
     */
    public function update(UserInterface $user, UserResponseInterface $response)
    {
        $owner = $response->getResourceOwner()->getName();
        $identifier = $response->getUsername();
        $token = $response->getAccessToken();

        if (null !== $oAuthToken = $this->findByIdentifier($owner, $identifier)) {
            if ($oAuthToken->getUser() !== $user) {
                $oAuthToken->setUser($user);
            }
        } elseif (null !== $oAuthToken = $this->findByUser($owner, $user)) {
            if ($oAuthToken->getIdentifier() !== $identifier) {
                $oAuthToken->setIdentifier($identifier);
            }
        } else {
            $oAuthToken = new OAuthToken();
            $oAuthToken
                ->setUser($user)
                ->setOwner($owner)
                ->setIdentifier($identifier);
        }

        $oAuthToken->setToken($token);

        if (!empty($in = $response->getExpiresIn())) {
            $expiresAt = new \DateTime();
            $expiresAt->setTimestamp(time() + 3599);
            $oAuthToken->setExpiresAt($expiresAt);
        } else {
            $oAuthToken->setExpiresAt(null);
        }

        $this->entityManager->persist($oAuthToken);
        $this->entityManager->flush();

        return $oAuthToken;
    }

    /**
     * Logout the by deleting the the oauth access token.
     *
     * @param UserInterface $user
     * @param string        $owner
     */
    public function logout(UserInterface $user, $owner)
    {
        if (null !== $oAuthToken = $this->findByUser($owner, $user)) {
            $oAuthToken->setToken(null);

            $this->entityManager->persist($oAuthToken);
            $this->entityManager->flush();
        }
    }

    /**
     * Finds the oauth token.
     *
     * @param string $owner
     * @param string $identifier
     *
     * @return null|OAuthToken
     */
    public function findByIdentifier($owner, $identifier)
    {
        /** @var OAuthToken $token */
        $token = $this->getTokenRepository()->findOneBy([
            'owner'      => $owner,
            'identifier' => $identifier,
        ]);

        return $token;
    }

    /**
     * Finds the oauth token.
     *
     * @param string        $owner
     * @param UserInterface $user
     *
     * @return null|OAuthToken
     */
    public function findByUser($owner, UserInterface $user)
    {
        /** @var OAuthToken $token */
        $token = $this->getTokenRepository()->findOneBy([
            'owner' => $owner,
            'user'  => $user,
        ]);

        return $token;
    }

    /**
     * Returns the OAuthToken repository.
     *
     * @return EntityRepository
     */
    private function getTokenRepository()
    {
        if (null !== $this->tokenRepository) {
            return $this->tokenRepository;
        }

        return $this->tokenRepository = $this->entityManager->getRepository(OAuthToken::class);
    }
}
